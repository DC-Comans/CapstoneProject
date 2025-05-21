    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link rel="stylesheet" href="/css/main.css">



        <style>
          .custom-success {
          background-color: #d4edda;
          border-left: 5px solid #28a745;
          color: #155724;
          padding: 15px;
          border-radius: 4px;
          margin-bottom: 15px;
      }

      .custom-failure {
          background-color: #f8d7da;
          border-left: 5px solid #dc3545;
          color: #721c24;
          padding: 15px;
          border-radius: 4px;
          margin-bottom: 15px;
      }

      html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    background-image: url('/images/homebackground.png');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    flex-direction: column;
}

main {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-end; /* cards lower */
    align-items: center;
    padding-bottom: 100px; /* space above footer */
}

.card-container {
    display: flex;
    justify-content: center;
    gap: 100px;
    margin-top: 550px;
    font-family: 'Patrick Hand', cursive;
    
}

.info-card {
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 30px;
    padding: 30px;
    width: 250px;
    background: linear-gradient(90deg,rgba(255, 0, 0, 1) 0%, rgba(255, 148, 148, 1) 100%, rgba(237, 221, 83, 1) 0%);
    
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
}

.info-card:hover {
    transform: scale(1.05);
}

footer {
    background-color: aliceblue;
    color: black;
    padding: 15px;
    text-align: center;
}


        </style>

    </head>
    <body style="background-image: url('/images/homebackground.png'); background-size: cover; background-repeat: no-repeat;">
      <x-layout2>

        <main>
        <div class="card-container">
        <div class="info-card" style="opacity: 0.8;">
          <p style="color: black">"I never though a quiz about death could make me feel more alive. It helped me reflect and opened conversations I have been avoiding for years."</p>
          <h3 style="color: black">Amira, 34</h3>
        </div>
        <div class="info-card " style="opacity: 0.8;">
          <p style="color: black;">"These quizzes made death feel less scary and more natural. I've even started planning my end of life wishes, and it feels empowering."</p>
          <h3 style="color: black">Jason, 41</h3>
        </div>
        <div class="info-card" style="opacity: 0.8;">
          <p style="color: black">"I took a quiz out of curiosity and ended up learning so much about myself and how I view loss. It was surprisingly comforting."</p>
          <h3 style="color: black">Rina, 28</h3>
        </div>
      </div>

        @if (session('success'))
            <div class="custom-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('failure'))
            <div class="custom-failure">
                {{ session('failure') }}
            </div>
        @endif

      @auth





      @else
      <div class="container">
        <div class="outer-box">
        <div class="login-box">
          <form>
            <label for="username"><strong>Username:</strong></label>
            <input type="text" id="username" name="username">
    
            <label for="password"><strong>Password:</strong></label>
            <input type="password" id="password" name="password">
    
            <button type="submit" class="btn login-btn">Login</button>
          </form>
        </div>
    
        <div class="signup-box">
          <span>Don't have an account yet? Just Sign up</span>
          <a href="sign-up"><button class="btn signup-btn" >Sign Up</button> </a>
          
        </div>
    </div>
      </div>
      @endauth

        
           
            
            
        </main>
        <footer>
    <p>HELP Application. All rights reserved. {{ date('Y') }}</p>
  </footer>
        </body>
                </html>
        </x-layout2>


  