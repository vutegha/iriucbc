<footer class="bg-black text-gray-300 py-10">
  <div class="max-w-7xl mx-auto px-6">
    <div class="flex flex-col md:flex-row justify-between items-stretch gap-12">
      <div class=" lg:flex items-center justify-between rounded-xl shadow-md w-full">

        <!-- Logo + Mission -->
        <div class="flex-1 max-w-sm flex flex-col">
          <div class="flex items-center mb-4">
            <img src="{{ asset('assets/img/logos/ucbc-2.png') }}" alt="Logo UCBC" class="h-12 w-12 rounded-full mr-3">
            <span class="text-lg font-semibold text-white">Institut de Recherche Intégré</span>
          </div>
          <p class="text-sm text-gray-400">
            Œuvrant pour la transformation intégrale en RDC à travers la recherche, l’innovation et l’impact communautaire.
          </p>
        </div>

        <!-- Navigation -->
        <div class="flex-1 flex flex-col">
          <h4 class="text-white font-semibold mb-4">Navigation</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="{{ url('/') }}" class="hover:underline">Accueil</a></li>
            <li><a href="{{ url('/about') }}" class="hover:underline">À propos</a></li>
            <li><a href="{{ url('/initiatives/programme-1') }}" class="hover:underline">Programme de Gouvernqnce de ressouces Naturelles</a></li>
            <li><a href="{{ url('/initiatives/programme-1') }}" class="hover:underline">Agribusiness and coffee and cocoa Lab</a></li>
            <li><a href="{{ url('/initiatives/programme-1') }}" class="hover:underline">Geolab</a></li>
            <li><a href="{{ url('/work-with-us') }}" class="hover:underline">Travailler avec nous</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="flex-1 flex flex-col">
          <h4 class="text-white font-semibold mb-4">Contact</h4>
          <ul class="space-y-2 text-sm text-gray-400">
            <li>Beni, Nord-Kivu, RDC</li>
            <li>Email : <a href="mailto:info@ucbc.org" class="hover:underline">info@ucbc.org</a></li>
            <li>Téléphone : +243 000 000 000</li>
          </ul>
          <form action="#" method="POST" class="mt-6 flex flex-col gap-2">
            @csrf
            <label for="newsletter-email" class="text-white text-sm">S'inscrire à la newsletter</label>
            <div class="flex">
              <input 
                type="email" 
                id="newsletter-email" 
                name="email" 
                required 
                placeholder="Votre adresse email" 
                class="rounded-l px-3 py-2 w-full text-black focus:outline-none"
              >
                <button 
                  type="submit" 
                  class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-r-lg border-2 border-yellow-500 shadow-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-300 text-sm"
                  style="box-shadow: 0 2px 8px 0 rgba(255, 193, 7, 0.3);"
                >
                  S'inscrire
                </button>
            </div>
            @error('email')
              <span class="text-red-400 text-xs">{{ $message }}</span>
            @enderror
            @if(session('newsletter_success'))
              <span class="text-green-400 text-xs">{{ session('newsletter_success') }}</span>
            @endif
          </form>
        </div>

        <!-- Suivez-nous -->
        <div class="flex-1 flex flex-col">
          <h4 class="text-white font-semibold mb-4">Suivez-nous</h4>
          <div class="flex space-x-4 text-lg">
            <a href="#" class="hover:text-white" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="hover:text-white" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="hover:text-white" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="hover:text-white" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
          </div>
        </div>

      </div>
    </div>

    <!-- Bottom line -->
    <div class="mt-10 border-t border-gray-800 pt-6 text-center text-sm text-gray-500">
      © {{ date('Y') }} UCBC – Tous droits réservés.
    </div>
  </div>
</footer>
