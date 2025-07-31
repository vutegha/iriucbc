<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * SYSTÈME DE MONITORING CONTINU DE SÉCURITÉ
 * Détecte automatiquement les anomalies de permissions
 */

class SecurityMonitor {
    
    private $alertThresholds = [
        'max_roles_per_user' => 2,
        'max_direct_permissions' => 0,
        'max_total_permissions' => 60,
        'max_admin_accounts' => 3
    ];
    
    private $alerts = [];
    
    public function runFullCheck() {
        echo "🔒 MONITORING SÉCURITÉ - " . now()->format('Y-m-d H:i:s') . "\n";
        echo str_repeat("=", 60) . "\n";
        
        $this->checkDirectPermissions();
        $this->checkMultipleRoles();
        $this->checkUnverifiedAdmins();
        $this->checkSuperPrivilegedUsers();
        $this->checkInactiveAccounts();
        $this->checkRoleDistribution();
        $this->checkSuspiciousActivity();
        
        $this->generateAlertReport();
        $this->saveMonitoringLog();
        
        return $this->alerts;
    }
    
    private function checkDirectPermissions() {
        echo "🔍 Vérification des permissions directes...\n";
        
        $usersWithDirectPermissions = User::whereHas('permissions')->with('permissions')->get();
        
        foreach ($usersWithDirectPermissions as $user) {
            $count = $user->permissions->count();
            $this->addAlert('direct_permissions', "HIGH", 
                "Utilisateur {$user->name} ({$user->email}) a {$count} permissions directes",
                ['user_id' => $user->id, 'permission_count' => $count]
            );
        }
        
        echo "   Résultat: " . $usersWithDirectPermissions->count() . " utilisateurs avec permissions directes\n";
    }
    
    private function checkMultipleRoles() {
        echo "🔍 Vérification des rôles multiples...\n";
        
        $usersWithMultipleRoles = User::whereHas('roles', function($query) {
            $query->havingRaw('COUNT(*) > ?', [$this->alertThresholds['max_roles_per_user']]);
        })->with('roles')->get();
        
        foreach ($usersWithMultipleRoles as $user) {
            $roleCount = $user->roles->count();
            $roleNames = $user->roles->pluck('name')->implode(', ');
            
            $this->addAlert('multiple_roles', "MEDIUM",
                "Utilisateur {$user->name} a {$roleCount} rôles: {$roleNames}",
                ['user_id' => $user->id, 'role_count' => $roleCount, 'roles' => $roleNames]
            );
        }
        
        echo "   Résultat: " . $usersWithMultipleRoles->count() . " utilisateurs avec rôles multiples\n";
    }
    
    private function checkUnverifiedAdmins() {
        echo "🔍 Vérification des admins non vérifiés...\n";
        
        $unverifiedAdmins = User::whereNull('email_verified_at')
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'super-admin', 'moderator']);
            })->with('roles')->get();
        
        foreach ($unverifiedAdmins as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            
            $this->addAlert('unverified_admin', "CRITICAL",
                "Admin non vérifié: {$user->name} ({$user->email}) - Rôles: {$roles}",
                ['user_id' => $user->id, 'roles' => $roles]
            );
        }
        
        echo "   Résultat: " . $unverifiedAdmins->count() . " admins non vérifiés\n";
    }
    
    private function checkSuperPrivilegedUsers() {
        echo "🔍 Vérification des utilisateurs super-privilégiés...\n";
        
        $users = User::with(['roles', 'permissions'])->get();
        
        foreach ($users as $user) {
            $totalPermissions = $user->getAllPermissions()->count();
            
            if ($totalPermissions > $this->alertThresholds['max_total_permissions']) {
                $this->addAlert('super_privileged', "HIGH",
                    "Utilisateur super-privilégié: {$user->name} a {$totalPermissions} permissions",
                    ['user_id' => $user->id, 'permission_count' => $totalPermissions]
                );
            }
        }
        
        $superUsers = array_filter($this->alerts, function($alert) {
            return $alert['type'] === 'super_privileged';
        });
        
        echo "   Résultat: " . count($superUsers) . " utilisateurs super-privilégiés\n";
    }
    
    private function checkInactiveAccounts() {
        echo "🔍 Vérification des comptes inactifs...\n";
        
        // Vérifier si la colonne exists
        try {
            $inactiveUsersWithPermissions = User::where('is_active', false)
                ->where(function($query) {
                    $query->whereHas('roles')->orWhereHas('permissions');
                })->get();
            
            foreach ($inactiveUsersWithPermissions as $user) {
                $this->addAlert('inactive_with_permissions', "MEDIUM",
                    "Compte inactif avec permissions: {$user->name} ({$user->email})",
                    ['user_id' => $user->id]
                );
            }
            
            echo "   Résultat: " . $inactiveUsersWithPermissions->count() . " comptes inactifs avec permissions\n";
        } catch (Exception $e) {
            echo "   ⚠️ Colonne 'is_active' non trouvée, vérification ignorée\n";
        }
    }
    
    private function checkRoleDistribution() {
        echo "🔍 Analyse de la distribution des rôles...\n";
        
        $adminCount = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->count();
        
        $superAdminCount = User::whereHas('roles', function($query) {
            $query->where('name', 'super-admin');
        })->count();
        
        $totalAdmins = $adminCount + $superAdminCount;
        
        if ($totalAdmins > $this->alertThresholds['max_admin_accounts']) {
            $this->addAlert('too_many_admins', "MEDIUM",
                "Trop de comptes administrateurs: {$totalAdmins} (seuil: {$this->alertThresholds['max_admin_accounts']})",
                ['admin_count' => $adminCount, 'super_admin_count' => $superAdminCount]
            );
        }
        
        echo "   Résultat: {$adminCount} admins, {$superAdminCount} super-admins\n";
    }
    
    private function checkSuspiciousActivity() {
        echo "🔍 Détection d'activités suspectes...\n";
        
        // Utilisateurs avec des combinaisons de rôles suspectes
        $suspiciousUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'user');
        })->whereHas('roles', function($query) {
            $query->whereIn('name', ['admin', 'super-admin']);
        })->with('roles')->get();
        
        foreach ($suspiciousUsers as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            $this->addAlert('suspicious_role_combination', "HIGH",
                "Combinaison suspecte de rôles: {$user->name} a les rôles: {$roles}",
                ['user_id' => $user->id, 'roles' => $roles]
            );
        }
        
        echo "   Résultat: " . $suspiciousUsers->count() . " combinaisons suspectes détectées\n";
    }
    
    private function addAlert($type, $severity, $message, $metadata = []) {
        $this->alerts[] = [
            'type' => $type,
            'severity' => $severity,
            'message' => $message,
            'metadata' => $metadata,
            'timestamp' => now()->toISOString()
        ];
    }
    
    private function generateAlertReport() {
        echo "\n🚨 RAPPORT D'ALERTES\n";
        echo str_repeat("=", 60) . "\n";
        
        if (empty($this->alerts)) {
            echo "✅ Aucune alerte détectée - Système sécurisé\n";
            return;
        }
        
        $alertsBySeverity = [
            'CRITICAL' => [],
            'HIGH' => [],
            'MEDIUM' => [],
            'LOW' => []
        ];
        
        foreach ($this->alerts as $alert) {
            $alertsBySeverity[$alert['severity']][] = $alert;
        }
        
        foreach ($alertsBySeverity as $severity => $alerts) {
            if (!empty($alerts)) {
                $icon = $this->getSeverityIcon($severity);
                echo "\n{$icon} {$severity} (" . count($alerts) . " alertes)\n";
                echo str_repeat("-", 40) . "\n";
                
                foreach ($alerts as $alert) {
                    echo "• {$alert['message']}\n";
                }
            }
        }
        
        echo "\n📊 RÉSUMÉ:\n";
        echo "Total alertes: " . count($this->alerts) . "\n";
        foreach ($alertsBySeverity as $severity => $alerts) {
            if (!empty($alerts)) {
                echo "- {$severity}: " . count($alerts) . "\n";
            }
        }
    }
    
    private function getSeverityIcon($severity) {
        return match($severity) {
            'CRITICAL' => '🔴',
            'HIGH' => '🟠',
            'MEDIUM' => '🟡',
            'LOW' => '🔵',
            default => '⚪'
        };
    }
    
    private function saveMonitoringLog() {
        $logData = [
            'monitoring_date' => now()->toISOString(),
            'total_alerts' => count($this->alerts),
            'alerts_by_severity' => [],
            'thresholds_used' => $this->alertThresholds,
            'alerts' => $this->alerts
        ];
        
        // Compter par sévérité
        foreach ($this->alerts as $alert) {
            $severity = $alert['severity'];
            if (!isset($logData['alerts_by_severity'][$severity])) {
                $logData['alerts_by_severity'][$severity] = 0;
            }
            $logData['alerts_by_severity'][$severity]++;
        }
        
        $logFile = 'security_monitoring_' . date('Y-m-d_H-i-s') . '.json';
        file_put_contents($logFile, json_encode($logData, JSON_PRETTY_PRINT));
        
        echo "\n📝 Log de monitoring sauvegardé: {$logFile}\n";
    }
}

// Exécution du monitoring
echo "🚀 DÉMARRAGE DU MONITORING DE SÉCURITÉ\n\n";

$monitor = new SecurityMonitor();
$alerts = $monitor->runFullCheck();

// Recommandations basées sur les alertes
echo "\n💡 RECOMMANDATIONS AUTOMATIQUES:\n";

if (count($alerts) === 0) {
    echo "✅ Système sécurisé - Aucune action requise\n";
} else {
    $criticalAlerts = array_filter($alerts, fn($a) => $a['severity'] === 'CRITICAL');
    $highAlerts = array_filter($alerts, fn($a) => $a['severity'] === 'HIGH');
    
    if (!empty($criticalAlerts)) {
        echo "🔴 ACTIONS URGENTES REQUISES:\n";
        echo "   - Exécuter: php correction_globale_permissions.php\n";
        echo "   - Vérifier manuellement les comptes critiques\n";
    }
    
    if (!empty($highAlerts)) {
        echo "🟠 ACTIONS RECOMMANDÉES:\n";
        echo "   - Réviser les permissions des utilisateurs flaggés\n";
        echo "   - Implémenter des contrôles supplémentaires\n";
    }
}

echo "\n🔄 PROGRAMMATION DU MONITORING:\n";
echo "• Monitoring quotidien: Ajouter ce script à un cron job\n";
echo "• Alertes email: Intégrer avec un système de notification\n";
echo "• Tableau de bord: Créer une interface web pour visualiser les alertes\n";

echo "\n=== MONITORING TERMINÉ ===\n";
