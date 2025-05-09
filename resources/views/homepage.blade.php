    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>
      <x-layout>
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


  