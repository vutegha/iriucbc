# Rapport de Correction - Erreur formulaire de candidature

## 🔍 Problème identifié
Erreur lors de la soumission de candidature sur `http://127.0.0.1:8000/jobs/1/apply` au clic sur "Soumettre la candidature".

## ✅ Corrections effectuées

### 1. JobApplicationRequest.php - Validation unique email
**Problème**: La règle `unique` utilisait `$this->route('job')` qui ne fonctionnait pas avec la liaison de modèle par slug.

**Solution**: Remplacement par une validation personnalisée avec closure :
```php
'email' => [
    'required',
    'email', 
    'max:255',
    function ($attribute, $value, $fail) {
        $job = $this->route('job');
        if ($job instanceof \App\Models\JobOffer) {
            $exists = \App\Models\JobApplication::where('email', $value)
                ->where('job_offer_id', $job->id)
                ->exists();
            if ($exists) {
                $fail('Vous avez déjà postulé pour cette offre d\'emploi.');
            }
        }
    }
]
```

### 2. Correspondance des valeurs de genre
**Problème**: Incohérence entre les valeurs du formulaire et celles de la base de données.

**Corrections**:
- **Validation**: `'gender' => 'nullable|in:M,F,Autre'` → `'gender' => 'nullable|in:masculin,feminin,autre'`
- **Vue**: Options changées de `value="M"` vers `value="masculin"`, etc.

### 3. Structure vérifiée
- ✅ Routes correctement configurées (`GET` et `POST /jobs/{job}/apply`)
- ✅ Contrôleur `SiteController::submitJobApplication()` intact
- ✅ Modèle `JobOffer` utilise bien le slug comme clé de route
- ✅ Migration `job_applications` compatible
- ✅ Méthode `incrementApplications()` présente dans le modèle

## 🧪 Validation des corrections
Les corrections permettent de résoudre :
1. L'erreur de validation unique de l'email
2. L'incompatibilité des valeurs de genre entre formulaire et base de données
3. La compatibilité avec la liaison de modèle par slug

## 🎯 Résultat attendu
Le formulaire de candidature sur `http://127.0.0.1:8000/jobs/1/apply` devrait maintenant fonctionner correctement lors du clic sur "Soumettre la candidature".

---
*Correction effectuée le 5 août 2025*
