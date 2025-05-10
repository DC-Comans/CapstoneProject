<header>
    <div class="logo">Healthy End of Life Planning</div>
    <nav>
      <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/quiz">Take Quiz</a></li>
        <li><a href="/resources">Resources</a></li>
        <li><a href="/about">About Us</a></li>
        <li><a href="/contact">Contact</a></li>
        @auth
        <li><a href="/account/{{auth()->user()->id}}">Account</a></li>
        <li><a href="/logout">Log Out</a></li>

        @endauth
      </ul>
    </nav>
</header>

<!-- Top of page -->

{{$slot}}

<!-- Bottom of page -->
<footer>
    <p>HELP Application. All rights reserved. {{date('Y')}}</p>

</footer>