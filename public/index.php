<?php

// 本项目不需要 index.php，因此给故意进来的小伙伴一个惊喜。
// 跳转到本项目的 GitHub 页面。

http_response_code(302);

header("Location: https://github.com/Cerallin/visitor-counter");

die("Permission denied.");
