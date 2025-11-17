<div>
 <div class="flex items-center space-x-4" data-aos="fade-left" data-aos-duration="1000"
                        data-aos-delay="200">
                        @if (Auth::check())
                            @if (!Auth::user()->hasRole(['Paciente']))
                                <a href="{{ route('admin.dashboard') }}"
                                    class="text-gray-300 hover:text-nevora-green transition-colors">
                                    <i class="fa-solid fa-gauge me-2"></i>Dashboard
                                </a>
                            @endif
                            @if (Auth::user()->hasRole(['Paciente']))
                                <a href="{{ route('dashboard') }}"
                                    class="text-gray-300 hover:text-nevora-green transition-colors">
                                    <i class="fa-solid fa-gauge-simple me-2"></i>Panel
                                </a>
                            @endif
                            <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                                class="bg-nevora-green text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors font-medium">
                                <i class="fa-solid fa-user me-2"></i>{{ Auth::user()->name }}
                                {{ Auth::user()->last_name }} <i class="fa-solid fa-chevron-down ms-2"></i>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="dropdownNavbar"
                                class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownLargeButton">
                                    <li>
                                        @if (!Auth::user()->hasRole(['Paciente']))
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a>
                                        @endif
                                        @if (Auth::user()->hasRole(['Paciente']))
                                        <a href="{{ route('dashboard') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-gauge-simple me-2"></i>Panel</a>
                                        @endif
                                    </li>
                                    <li>
                                        @if (Auth::user()->hasRole(['Paciente']))
                                        <a href="{{ route('settings.profile') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-user me-2"></i>Perfil</a>
                                        @endif
                                        @if (!Auth::user()->hasRole(['Paciente']))
                                        <a href="{{ route('settings.profile') }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"><i class="fa-solid fa-user me-2"></i>Perfil</a>
                                        @endif
                                    </li>
                                </ul>
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i>Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="bg-nevora-green text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors font-medium">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Iniciar Sesión
                            </a>
                        @endif
                    </div>
                </div>



</div>
