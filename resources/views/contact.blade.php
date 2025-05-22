<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Us</title>
        <link rel="stylesheet" href="/css/main.css">


        <style>
     body {
        background: linear-gradient(to bottom right, #f0fdf4, #e0f7fa);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        margin: 0;
        padding: 0;
    }

   h1{
            font-size: 2.5rem;
            color: #2f4f4f;
            margin-bottom: 10px;
   }

    

    
</style>

    </head>
    <body>

        <x-layout>


            <h1 style="margin-left: 20px">Get in touch with us</h1>

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


  