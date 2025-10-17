<!DOCTYPE html>
<html>
  <head>
    <title>Best Viewed at: Your Screen Resolution!</title>
    <link rel="stylesheet" href="./best-resolution.css">
  </head>
  <body>
    <svg width="0" height="0" style="display: block;" viewBox="-40 -40 80 80">
      <defs>
        <filter id="stickerize">
          <feFlood flood-color="white" result="solid"/>
          <feMorphology in="SourceAlpha" result="dilated" operator="dilate" radius="3"/>
          <feComposite in="solid" in2="dilated" operator="in"/>
          <feComposite in="SourceGraphic"/>
        </filter>
      </defs>
    </svg>
    <header>
      <h1>Best Viewed at: <span class="resolution">Your Screen Resolution!</span></h1>
    </header>
    <main>
      <section>
        <h2>View with your eyes</h2>
        <p>
          This page
          <?php if( isset($_GET['from']) ) { ?>
            (like <span class="referer">the one you came from</span>!)
          <?php } ?>
          is best viewed at... <em>any</em> screen resolution!
        </p>
        <p class="resolution-extended"></p>
        <p style="text-align: center;">
          <img src="//best-resolution.danq.dev/any.gif" alt="Looks best at: any resolution!" width="176" height="62">
          <script src="//best-resolution.danq.dev/script.js"></script>
        </p>
      </section>

      <section>
        <h2>Get the 88×31!</h2>
        <p>
          Does your website work regardless of your visitor's screen resolution, too?
        </p>
        <p>
          Want to celebrate that fact with an 88×31 that <em>adapts</em> to
          <strong>show your visitors their specific screen resolution</strong>?
        </p>
        <p>
          Just add this code to your website:
        </p>
        <code>
          &lt;img src="//best-resolution.danq.dev/any.gif" alt="Looks best at: any resolution!" width="88" height="31"&gt;<br>
          &lt;script src="//best-resolution.danq.dev/script.js"&gt;&lt;/script&gt;
        </code>
        <h3>Tips</h3>
        <ul>
          <li>
            You can put the image on your page as often as you like, but you only need the script to appear once: it'll automatically
            update all of the 'any size' images, if possible, to the visitor's resolution... even if they resize their window!
          </li>
          <li>
            No JavaScript? No problem! If the visitor has JavaScript disabled, they'll still see a button that says "best at any size"!
            (The same will happen for extremely unusual high/low sizes, in theory.)
          </li>
          <li>
            The script can be loaded early or late, deferred or synchronous; it's not fussy.
          </li>
          <li>
            After the initial image is loaded, the replacement image with the visitor's resolution is preloaded in the background so
            it can be swapped-in seamlessly. This is <em>usually</em> faster than the GIF animation gets to the "any size" frame, so
            it looks las though you knew their screen resolution all along!
          </li>
          <li>
            Why do links back to this site get a <tt>?from=...</tt> parameter? It's not an attempt to track you, I promise! It's just
            so it can "highlight" to people who came from your site.
          </li>
        </ul>
      </section>

      <section>
        <h2>Who's using this?</h2>
        <p>
          Here are some of the websites that use this 88×31. Go visit them! (List reshuffles every three hours.)
        </p>
        <p class="buttons">
          <?php
            function load_buttons($file){
              $sites = file_get_contents($file);
              $buttons = array_map("trim", explode("\n", $sites));
              $buttons = array_filter($buttons, fn($button) => $button);
              return $buttons;
            }

            function parse_button_data($buttons){
              $button_data = [];
              foreach($buttons as $button) {
                $button = preg_split("/\s{2,}/", $button);
                $button_data[] = [ 'alt' => $button[0], 'href' => $button[1], 'src' => $button[2] ];
              }
              return $button_data;
            }

            if( ! file_exists('current-sites.txt') || (time() - filemtime('current-sites.txt') > 3600) ) { // 3 hour expiry before sites rotated
              $all_buttons = load_buttons('all-sites.txt');
              shuffle($all_buttons);
              file_put_contents('current-sites.txt', implode("\n", array_slice($all_buttons, 0, 6)));
            }
            if( file_exists('current-sites.txt') ) {
              $buttons = load_buttons('current-sites.txt');
              $button_data = parse_button_data($buttons);

              if( $_GET['from'] ) { // If we came FROM a site in our known sites list, make sure that's shown this load!
                // First, try the exising "current list"; if it's there, just highlight it!
                $button_index = array_find_key($button_data, fn($button) => str_starts_with($_GET['from'], $button['href']));
                if( $button_index ) {
                  $button_data[$button_index]['extra_html'] = ' class="from"';
                } else {
                  // Otherwise, let's try to find it in the "all sites" list!
                  $all_buttons = load_buttons('all-sites.txt');
                  $all_button_data = parse_button_data($all_buttons);
                  $all_button_index = array_find_key($all_button_data, fn($button) => str_starts_with($_GET['from'], $button['href']));
                  $all_button_item = $all_button_data[$all_button_index];
                  if( $all_button_item ) {
                    // Found it: so let's swap out an item from the current list with this new one, for this page load only:
                    $position = $all_button_index % count($button_data);
                    $button_data[$position] = $all_button_item;
                    $button_data[$position]['extra_html'] = ' class="from"';
                  }
                }
              }

              foreach($button_data as $button) {
                echo " <a href=\"{$button['href']}\"{$button['extra_html']}><img src=\"{$button['src']}\" alt=\"{$button['alt']}\" width=\"88\" height=\"31\"></a> ";
              }
            } else {
              echo "<p>We can't show a list of sites right now. Sorry!</p>";
            }
          ?>
        </p>
        <p>
          Want your button to show up here? <a href="mailto:best-resolution@danq.me">Email me</a> the name and address of your site and
          the URL of the 88×31 image you want me to use, and I'll see about adding it. So long as your site is legal and not objectively
          offensive, it'll be added quickly.
          <br>
          <small>
            (Alternatively, you can make a pull request against
            <a href="https://github.com/Dan-Q/best-resolution/blob/main/all-sites.txt">this file</a>,
            adding your own site.)
          </small>
        </p>
      </section>
    </main>
    <footer class="buttons">
      <a href="https://danq.me/"><img src="/badges/dan-q-88x31-lighter.gif" alt="Dan Q's website" width="88" height="31"></a>
      <a href="https://forum.melonland.net/"><img src="https://forum.melonland.net/images/MELONLAND-FORUM.GIF" alt="Discuss on Melonland Forum" width="88" height="31"></a>
      <img src="//best-resolution.danq.dev/any.gif" alt="Looks best at: any resolution!" width="88" height="31">
      <img src="/badges/best-eyes.gif" alt="Best viewed with eyes!" width="88" height="31">
      <a href="https://github.com/dan-q/best-resolution"><img src="/badges/github.png" alt="Source code available on GitHub!" width="88" height="31"></a>
    </footer>

    <script>
      function updateResolution(){
        const width = window.innerWidth;
        const height = window.innerHeight;
        const resolution = `${width}x${height} pixels`;
        document.querySelectorAll('.resolution').forEach(element => element.innerText = resolution);
        document.querySelectorAll('.resolution-extended').forEach(element => element.innerHTML = `That includes <em>your</em> screen resolution of ${resolution}!`);
        document.title = `Best Viewed at: ${resolution}`;
      };
      document.addEventListener('DOMContentLoaded', updateResolution);
      window.addEventListener('resize', updateResolution);

      for(const referer of document.querySelectorAll('.referer')) {
        referer.innerHTML = `<a href="#" onclick="history.back();">${referer.innerText}</a>`;
      }
    </script>
  </body>
</html>
