<!doctype html>
<html class="no-js" lang="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Intent detection with wit.ai</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/bootstrap.min.css">
  <style>
    body {
      padding-top: 50px;
      padding-bottom: 20px;
    }
  </style>
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="css/main.css">

  <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>
<body>
<?php

$userInput = clean(isset($_REQUEST['userInput']) ? $_REQUEST['userInput'] : '');
$links = [
  'intent' => [
    'salutation' => 'You proably want to say "hi" back',
  ],
  'manuals' => [
    'advice' => 'https://www.gov.uk/hmrc-internal-manuals/admin-law-manual',
    'Admin Law Manual' => 'https://www.gov.uk/hmrc-internal-manuals/admin-law-manual',
    'Aggregates Levy Guidance' => 'https://www.gov.uk/hmrc-internal-manuals/aggregates-levy'
  ]
]; ?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed"
              data-toggle="collapse" data-target="#navbar" aria-expanded="false"
              aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">Intent</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
    </div><!--/.navbar-collapse -->
  </div>
</nav>
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <h2>Intent detection with wit.ai</h2>
      <hr/>
      <form style="border: 1px solid #ccc; padding: 10px;" action="index.php"
            method="GET">
        <div class="form-group">
          <label for="exampleInput">Type your query</label>
          <input type="text" name="userInput" value="<?php print $userInput; ?>"
                 class="form-control" id="exampleInput"
                 aria-describedby="fieldHelp" placeholder="Enter text">
          <small id="fieldHelp" class="form-text text-muted">Mix and match from
            the examples from the bottom of the page. They have been trained as
            displayed.
          </small>
        </div>
        <input type="submit" class="btn btn-primary"/>
        <input type="reset" class="btn btn-primary" value="Reset"/>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <hr/>
      <?php
      require_once __DIR__ . '/vendor/autoload.php';
      require_once 'app_key.php';

      use Tgallice\Wit\Client;
      use Tgallice\Wit\MessageApi;

      $client = new Client($key);


      $intent = '';

      if (!empty($userInput)) {
        $response = $client->get('/message', [
          'q' => $userInput,
        ]);

        // Get the decoded body
        $intent = json_decode((string) $response->getBody(), TRUE);

        if (empty($intent['entities'])) {
          ?>
          <div class="alert alert-info" role="alert">I don't know what you mean.
            Try again!
          </div><?php
        }
        else {
          print "<table class='table' border='1'><thead><tr><th>Type</th><th>Value</th><th>Confidence</th></tr></thead><tbody>";
          foreach ($intent['entities'] as $k => $entity) {
            $v = reset($entity);
            print "<tr><td>" . $k . "</td><td>" . $v['value'] . "</td><td>" . $v['confidence'] . "</td></tr>";
            if (isset($links[$k][$v['value']])) {
              print "<tr><td colspan='3'>" . "Response: <br /><strong>" . $links[$k][$v['value']] . "</strong><br />or search for
              <strong><em>$userInput</em></strong> in the <strong>" .
                $v['value'] . "</strong> section of <strong>$k</strong>.</td></tr>";
            }
          }
          print "</tbody></table>";

          if (isset($links['$k'][$v['value']])) {
            echo "Link to " . $links['$k'][$v['value']];
          }
        }

        print "<p>Result:</p><pre style='max-height: 200px'>";
        print_r($intent);
        print "</pre>";
      }
      else {
        ?>
        <div class="alert alert-danger" role="alert">Your input is empty!
        </div><?php
      }

      function clean($string) {
        return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
      }

      ?>
    </div>
  </div>

  <hr>

  <h3>Admin Law Manual</h3>
  <a href="https://www.gov.uk/hmrc-internal-manuals/admin-law-manual">https://www.gov.uk/hmrc-internal-manuals/admin-law-manual</a>

  <p>Try:</p>
  <ol>
    <li><a href="/?userInput=statements%20of%20practice">statements of
        practice</a></li>
    <li><a href="/?userInput=incorrect%20advice">incorrect advice</a></li>
    <li><a href="/?userInput=collection and management">collection and
        management</a></li>
    <li><a href="/?userInput=extra+statutory+concessions">extra statutory
        concessions</a></li>
    <li><a href="/?userInput=incorrect+case-specific+advice">incorrect
        case-specific advice</a></li>
  </ol>
  <hr>

  <h3>Aggregates Levy Guidance</h3>
  <a href="https://www.gov.uk/hmrc-internal-manuals/aggregates-levy">https://www.gov.uk/hmrc-internal-manuals/aggregates-levy</a>

  <p>Try:</p>
  <ol>
    <li><a href="/?userInput=assurance of accounting system">assurance of
        accounting system</a></li>
    <li><a href="/?userInput=assurance of site operations">assurance of site
        operations</a></li>
  </ol>


  <footer>
    <p>&copy; Nick Opris 2019</p>
  </footer>
</div> <!-- /container -->
<script
  src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

<script src="js/vendor/bootstrap.min.js"></script>

<script src="js/main.js"></script>
</body>
</html>
