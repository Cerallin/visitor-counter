# visitor-counter

A php-based visitor counter

## Usage

```sh
$ curl -s http://your.site/page.php -H 'Referer: https://another.site/patj/to/page' | jq
{
  "pv": "4",
  "uv": "1"
}
```

## Installation

This software requires `PHP>=7.4` and extension `PDO` installed, and the `SQLs` were only tested with MySQL.

```sh
git clone https://github.com/Cerallin/visitor-counter
cp config.example.ini config.ini # and change the settings
```

Document directory (configured in Apache or Nginx) should end with `public`.

Then the backend part is ready to go.

Next, it's time to show up PVs and UVs in your site. There's a demo below:

```html
<div>
  <span id='pv'></span>
  PV
  <span id='uv'></span>
  UV
</div>

<script>
let xhr = new XMLHttpRequest();
xhr.open('GET', 'http://visitor-counter-dev.cerallin.top/total.php', false);
xhr.onreadystatechange = function () {
  if (xhr.readyState == 4 && xhr.status == 200) {
    try {
      window.addEventListener('load', (event) => {
        const counter = JSON.parse(xhr.response);
        document.getElementById('pv').innerText = counter.pv;
        document.getElementById('uv').innerText = counter.uv;
      })
    } catch (e) {
      console.error("Cannot parse JSON.");
    }
  }
}
xhr.send();
</script>
```

All done.
