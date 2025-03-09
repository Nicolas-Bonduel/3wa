<footer id="footer">

    @vite(['resources/css/footer.scss'])


    <div class="content">
        <section class="head">
            <img class="logo" alt="My Electronics" src="{{ asset('img/logo.png') }}" width="200" height="50" />
            <p>
                My Electronics, leader en matériel électronique professionnel, maintenance et reconditionnement deuis 1996.
                Solutions écologiques et durables, certification label RecQ : Reconditionnement de Qualité.
            </p>
        </section>

        <section class="contact">
            <span class="uppercase">
                My Electronics et Vous
            </span>
            <ul class="mb-3 pl-2">
                <li>
                    <a href="{{ route('customer.dashboard') }}" wire:navigate>
                        Mon Compte
                    </a>
                </li>
                <li>
                    <a href="{{ route('public.recruitment') }}" wire:navigate>
                        Recrutement
                    </a>
                </li>
                <li>
                    <a href="{{ route('public.faq') }}" wire:navigate>
                        FAQ
                    </a>
                </li>
            </ul>

            <span class="uppercase">
                Une équipe à votre écoute
            </span>
            <span class="flex flex-col pl-2">
                1 Rue de la Rue, 87160 Arnac-La-Poste <br/>
                <b onclick="window.open('tel:003401020304')">+33(0) 4 01 02 03 04</b>
                <a href="mailto:contact@myelectronics.com">contact@myelectronics.com</a>
            </span>
        </section>

        <section class="legals">
            <span class="uppercase">
                Informations
            </span>
            <ul class="mb-3 pl-2">
                <li>
                    <a href="{{ route('public.terms-of-sale') }}" wire:navigate>
                        Conditions Générales de Vente
                    </a>
                </li>
                <li>
                    <a href="{{ route('public.customer-service') }}" wire:navigate>
                        Service Après-Vente
                    </a>
                </li>
                <li>
                    <a href="{{ route('public.legal-notice') }}" wire:navigate>
                        Mentions légales
                    </a>
                </li>
                <li>
                    <a href="{{ route('public.privacy-policy') }}" wire:navigate>
                        Politique de protection des données personnelles
                    </a>
                </li>
            </ul>

            <span class="uppercase">
                Moyens de Paiement
            </span>
            <img class="pl-2" alt="paiement visa, CB, PayPal, virement" src="{{ asset('img/payment_means.svg') }}" width="239" height="64" />
        </section>

        <section class="socials">
            <span class="uppercase">
                Newsletter
            </span>
            <p class="pl-2">
                Recevez les dernières actualités de matériel électronique industriel
            </p>
            <button class="btn-primary mt-1 mb-3 ml-2">
                S'inscrire
            </button>

            <span class="block uppercase mb-1">
                Retrouvez nous sur nos réseaux sociaux
            </span>
            <a class="inline-block pl-2" href="#" target="_blank">
                <img alt="logo LinkedIn" src="{{ asset('img/icon_linkedin.svg') }}" width="30" height="30"/>
            </a>
            <a class="inline-block pl-2" href="#" target="_blank">
                <img alt="logo Youtube" src="{{ asset('img/icon_youtube.svg') }}" width="43" height="30"/>
            </a>
        </section>
    </div>

    <div class="copyright">
        © {{ date('Y') }} My-Electronics
    </div>

</footer>
