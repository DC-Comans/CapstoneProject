<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Us</title>
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>

        <x-layout>


            <h1 style="color:black">Get in touch</h1>

            <div class="container">
                <div class="outer-box">
                <div class="login-box">
                  <form method="POST" action="/contact">
                    @csrf
                    <label for="name"><strong>Name:</strong></label>
                    <input type="text" id="name" name="name">

                    <label for="email"><strong>Email:</strong></label>
                    <input type="email" id="email" name="email">
            
                    <label for="text"><strong>How we can help:</strong></label>
                    
                    <textarea name="text" id="text"></textarea>

                    
            
                    <button type="submit" class="btn login-btn">Submit</button>
                  </form>
                </div>
            
                
            </div>
              </div>
            
            
              
        
        </body>
                </html>
        </x-layout>


  