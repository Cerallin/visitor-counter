# visitor-counter

A php-based visitor counter.

## 用法

假设本软件被部署到了`http://counter.site/`。

```sh
$ curl -s \
    -H 'Referer: https://another.site/' | jq \
    http://counter.site/count.php?page=https://another.site/path/to/page/
{
  "site_pv": 10,
  "site_uv": 7,
  "site_uv": 5,
  "site_uv": 3
}
```

## 安装

安装本软件需要 `PHP>=7.4` 且安装了 `PDO` 扩展。

**P.S.** 本软件中的SQL语句仅在MySQL中测试过。

```sh
git clone https://github.com/Cerallin/visitor-counter
cp config.example.ini config.ini # and change the settings
```

需要注意的是，本软件的入口为public文件夹，即Apache中的`DocumentRoot`，或是Nginx中`location`的`root`。

接下来在你的网站中使用JavaScript脚本发送异步请求并渲染到前端。下面是一个例子：

```html
<div>
  <span id='site_pv'></span>
  PV
  <span id='site_uv'></span>
  UV
</div>

<script async>
  let xhr = new XMLHttpRequest();
  xhr.open('GET', 'http://counter.site/count.php/?page=' + encodeURI(window.location.href), false);
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4) {
      if (xhr.status == 200) {
        try {
          const counter = JSON.parse(xhr.response);
          window.addEventListener('load', (event) => {
            for (let key in counter) {
              let dom = document.getElementById(key);
              if (dom)
                dom.innerText = counter[key];
            }
          })
        } catch (e) {
          console.error("Cannot parse JSON.")
        }
      }
    }
  }
  xhr.send();
</script>
```

All done.
