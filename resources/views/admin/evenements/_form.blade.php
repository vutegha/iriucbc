<!-- Formulaire pour événements -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informations de l'événement
                </h5>
            </div>
            <div class="card-body">
                <!-- Titre -->
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                           id="titre" name="titre" value="{{ old('titre', $evenement->titre ?? '') }}" 
                           placeholder="Titre de l'événement" required>
                    @error('titre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Résumé -->
                <div class="mb-3">
                    <label for="resume" class="form-label">Résumé</label>
                    <textarea class="form-control @error('resume') is-invalid @enderror" 
                              id="resume" name="resume" rows="3" 
                              placeholder="Résumé court de l'événement (optionnel)">{{ old('resume', $evenement->resume ?? '') }}</textarea>
                    @error('resume')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Maximum 500 caractères.</div>
                </div>

                <!-- Description complète -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="8" 
                              placeholder="Description détaillée de l'événement" required>{{ old('description', $evenement->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Lieu -->
                <div class="mb-3">
                    <label for="lieu" class="form-label">Lieu</label>
                    <input type="text" class="form-control @error('lieu') is-invalid @enderror" 
                           id="lieu" name="lieu" value="{{ old('lieu', $evenement->lieu ?? '') }}" 
                           placeholder="Lieu de l'événement">
                    @error('lieu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- URL du rapport -->
                <div class="mb-3">
                    <label for="rapport_url" class="form-label">URL du rapport</label>
                    <input type="url" class="form-control @error('rapport_url') is-invalid @enderror" 
                           id="rapport_url" name="rapport_url" value="{{ old('rapport_url', $evenement->rapport_url ?? '') }}" 
                           placeholder="https://exemple.com/rapport.pdf">
                    @error('rapport_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Lien vers un rapport ou document lié à l'événement.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Dates et horaires -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Dates et horaires
                </h5>
            </div>
            <div class="card-body">
                <!-- Date de début -->
                <div class="mb-3">
                    <label for="date_debut" class="form-label">Date de début <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('date_debut') is-invalid @enderror" 
                           id="date_debut" name="date_debut" 
                           value="{{ old('date_debut', isset($evenement) && $evenement->date_debut ? \Carbon\Carbon::parse($evenement->date_debut)->format('Y-m-d\TH:i') : '') }}" 
                           required>
                    @error('date_debut')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de fin -->
                <div class="mb-3">
                    <label for="date_fin" class="form-label">Date de fin</label>
                    <input type="datetime-local" class="form-control @error('date_fin') is-invalid @enderror" 
                           id="date_fin" name="date_fin" 
                           value="{{ old('date_fin', isset($evenement) && $evenement->date_fin ? \Carbon\Carbon::parse($evenement->date_fin)->format('Y-m-d\TH:i') : '') }}">
                    @error('date_fin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Optionnel. Laissez vide pour un événement d'une journée.</div>
                </div>
            </div>
        </div>

        <!-- Image -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-image me-2"></i>Image de l'événement
                </h5>
            </div>
            <div class="card-body">
                @if(isset($evenement) && $evenement->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $evenement->image) }}" 
                             alt="Image actuelle" class="img-fluid rounded" style="max-height: 200px;">
                        <div class="form-text mt-2">Image actuelle</div>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="image" class="form-label">{{ isset($evenement) && $evenement->image ? 'Nouvelle image' : 'Image' }}</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        Formats acceptés : JPG, JPEG, PNG, WebP. Taille max : 5 Mo.
                    </div>
                </div>

                <!-- Aperçu de l'image -->
                <div id="image-preview" style="display: none;">
                    <img id="preview-img" src="" alt="Aperçu" class="img-fluid rounded" style="max-height: 200px;">
                    <div class="form-text mt-2">Aperçu de la nouvelle image</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aperçu de l'image
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });

    // Validation des dates
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');

    dateDebut.addEventListener('change', function() {
        dateFin.min = this.value;
        if (dateFin.value && dateFin.value < this.value) {
            dateFin.value = '';
        }
    });
});
</script>
@endpush
