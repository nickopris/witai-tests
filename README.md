# A basic implementation of Wit.ai API

This is using https://github.com/tgallice/wit-php (thanks!)

Get your https://wit.ai/ key.

Add your web/app_key.php file with contents:

    <?php $key = 'Your wit.ai key here';
    
# Get started
Go to the `chatbot-docker` directory and run `docker-compose up -d`

From `web` run `composer install` so you can get the vendor libs down.

Got to your browser and hit:
http://chatbot.localhost:8000 

What you see on that page will not apply to your project because you need to use your own information to train your wit.ai app.

You need to go to your app on wit.ai site and train it a little. Then come back to this app and test it. Full JSON response will be shown on the page, even if my hardcoded logic will not kick in.

  
  