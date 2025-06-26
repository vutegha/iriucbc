<nav class="fixed top-0 z-50 w-full bg-white shadow">
  <div class="container mx-auto px-4 flex flex-wrap items-center justify-between py-3">
    <!-- Logo and Hamburger -->
    <div class="flex w-full justify-between lg:w-auto lg:static lg:block lg:justify-start">
      <a
        href="/"
        class="text-blueGray-700 text-sm font-bold leading-relaxed mr-4 py-2 whitespace-nowrap uppercase"
      >
        Notus React
      </a>
      <button
        class="lg:hidden text-xl px-3 py-1 rounded bg-transparent outline-none focus:outline-none"
        type="button"
        onclick="document.getElementById('navbar-content').classList.toggle('hidden')"
      >
        <i class="fas fa-bars"></i>
      </button>
    </div>
    <!-- Nav Links -->
    <div
      id="navbar-content"
      class="flex-grow items-center bg-white lg:bg-transparent lg:shadow-none hidden lg:flex"
    >
      <ul class="flex flex-col lg:flex-row list-none mr-auto">
        <li class="flex items-center">
          <a
            class="hover:text-blueGray-500 text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
            href="https://www.creative-tim.com/learning-lab/tailwind/react/overview/notus?ref=nr-index-navbar"
          >
            <i class="text-blueGray-400 far fa-file-alt text-lg mr-2"></i>
            Docs
          </a>
        </li>
      </ul>
      <ul class="flex flex-col lg:flex-row list-none lg:ml-auto">
        <li class="flex items-center">
          <!-- Replace with your dropdown HTML if needed -->
          <div class="relative">
            <button class="px-3 py-2 text-xs uppercase font-bold">Dropdown</button>
            <!-- Dropdown content here -->
          </div>
        </li>
        <li class="flex items-center">
          <a
            class="hover:text-blueGray-500 text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
            href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdemos.creative-tim.com%2Fnotus-react%2F%23%2F"
            target="_blank"
            rel="noopener noreferrer"
          >
            <i class="text-blueGray-400 fab fa-facebook text-lg"></i>
            <span class="lg:hidden ml-2">Share</span>
          </a>
        </li>
        <li class="flex items-center">
          <a
            class="hover:text-blueGray-500 text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
            href="https://twitter.com/intent/tweet?url=https%3A%2F%2Fdemos.creative-tim.com%2Fnotus-react%2F%23%2F&text=Start%20your%20development%20with%20a%20Free%20Tailwind%20CSS%20and%20React%20UI%20Kit%20and%20Admin.%20Let%20Notus%20React%20amaze%20you%20with%20its%20cool%20features%20and%20build%20tools%20and%20get%20your%20project%20to%20a%20whole%20new%20level.%20"
            target="_blank"
            rel="noopener noreferrer"
          >
            <i class="text-blueGray-400 fab fa-twitter text-lg"></i>
            <span class="lg:hidden ml-2">Tweet</span>
          </a>
        </li>
        <li class="flex items-center">
          <a
            class="hover:text-blueGray-500 text-blueGray-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
            href="https://github.com/creativetimofficial/notus-react?ref=nr-index-navbar"
            target="_blank"
            rel="noopener noreferrer"
          >
            <i class="text-blueGray-400 fab fa-github text-lg"></i>
            <span class="lg:hidden ml-2">Star</span>
          </a>
        </li>
        <li class="flex items-center">
          <button
            class="bg-lightBlue-500 text-white active:bg-lightBlue-600 text-xs font-bold uppercase px-4 py-2 rounded shadow hover:shadow-lg ml-3 mb-3 lg:mb-0 transition-all duration-150"
            type="button"
          >
            <i class="fas fa-arrow-alt-circle-down"></i> Download
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>
