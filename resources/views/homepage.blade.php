    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
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

        </style>

    </head>
    <body>
      <x-layout>

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
<h1 style="color:red">HELLO</h1>




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

        
           
            
            
              
        
        </body>
                </html>
        </x-layout>


  