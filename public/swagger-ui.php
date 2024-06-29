<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Swagger UI</title>
  <link rel="stylesheet" type="text/css"
    href="<?php echo $_ENV['BASE_PATH'] . '/public/' ?>swagger-ui/swagger-ui.css" />
  <style>
  body {
    margin: 0;
    padding: 0;
  }
  </style>
</head>

<body>
  <div id="swagger-ui"></div>
  <script src="<?php echo $_ENV['BASE_PATH'] . '/public/' ?>swagger-ui/swagger-ui-bundle.js"></script>
  <script src="<?php echo $_ENV['BASE_PATH'] . '/public/' ?>swagger-ui/swagger-ui-standalone-present.js"></script>
  <script>
  window.onload = function() {
    const ui = SwaggerUIBundle({
      url: 'swagger',
      dom_id: '#swagger-ui',
      presets: [
        SwaggerUIBundle.presets.apis,
        SwaggerUIStandalonePreset
      ],
      layout: "StandaloneLayout"
    });
    window.ui = ui;
  };
  </script>
</body>

</html>