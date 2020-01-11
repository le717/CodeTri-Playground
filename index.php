<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Playground | Caleb Ely</title>
  <meta name="theme-color" content="#63b3ed">
  <link rel="shortcut icon" href="favicon.png">
  <link rel="stylesheet" crossorigin="anonymous" href="https://fonts.googleapis.com/css?family=Raleway:300,400,400i,700|Zilla+Slab:600">
  <link rel="stylesheet" href="output.css">

<?php
  /**
  * @link {http://www.the-art-of-web.com/php/directory-list-spl/}
  */
  function getDemos($dir) {
    $folders = [];

    // add trailing slash if missing
    if (substr($dir, -1) !== "/") $dir .= "/";

    // Open directory for reading
    // TODO Return proper error so it can be handled
    $d = new DirectoryIterator($dir) or die("getDemos: Failed opening directory {$dir} for reading");

    foreach ($d as $fileInfo) {
      // Only get the directories below us
      if ($fileInfo->isDir() && !preg_match('/^[.]/', $fileInfo)) {
        $demo = new stdClass();
        $demo->path = "{$dir}{$fileInfo}";
        $demo->dirName = $fileInfo->getFilename();
        $demo->url = $_SERVER['SCRIPT_URI'] . "{$fileInfo}/";
        $folders[] = $demo;
      }
    }

    sort($folders);
    return $folders;
  }
?>
<body>
  <header>
    <div class="wordmark">
      <img class="logo" alt="Caleb Ely's logo" width="50" src="https://codetri.net/img/logo.svg">
      <h1 class="name">Caleb Ely</h1>
      <span class="divider">|</span>
      <h2 class="title">Playground</h2>
    </div>

    <nav>
      <ul class="flex text-base">
        <li>
          <a href="https://codetri.net">Home</a>
        </li>
        <li>
          <a href="https://blog.codetri.net">Blog</a>
        </li>
      </ul>
    </nav>
  </header>


  <main class="m-4">
    <p class="text-center my-4">You are currently roaming my playground! This is where I post public demos and small, one-off projects that don't require their own space. Have fun browsing! -Caleb</p>

    <section class="cards-wrapper">
    <?php
      $template = '<div class="card playground">
        <a class="title" href="::url::">::title::</a>
        <h2 class="label">::desc::</h2>
      </div>
      ';

      // Get the available demos
      $playground = getDemos(getcwd());

      foreach ($playground as $demo) {
        // Get the demo JSON and the contents
        $json = @json_decode(file_get_contents("{$demo->path}/demo.json"));
        $demoName = ($json === null || $json->name === '') ? $demo->dirName : $json->name;
        $demoDesc = ($json === null || !isset($json->desc)) ? 'No description avilable.' : $json->desc;

        // Fill in the placeholder content
        $card = str_replace('::url::', $demo->url, $template);
        $card = str_replace('::title::', $demoName, $card);
        $card = str_replace('::desc::', $demoDesc, $card);

        echo $card;
      }
    ?>
    </section>
  </main>

  <footer>😛</footer>
</body>
</html>
