<?php exit; ?>
1487158294
SELECT * FROM phpbb_bbcodes WHERE bbcode_id = 14
633
a:1:{i:0;a:10:{s:9:"bbcode_id";s:2:"14";s:10:"bbcode_tag";s:6:"center";s:15:"bbcode_helpline";s:11:"Center text";s:18:"display_on_posting";s:1:"0";s:12:"bbcode_match";s:23:"[center]{TEXT}[/center]";s:10:"bbcode_tpl";s:32:"<div align="center">{TEXT}</div>";s:16:"first_pass_match";s:31:"!\[center\](.*?)\[/center\]!ies";s:18:"first_pass_replace";s:140:"'[center:$uid]'.str_replace(array("\r\n", '\"', '\'', '(', ')'), array("\n", '"', '&#39;', '&#40;', '&#41;'), trim('${1}')).'[/center:$uid]'";s:17:"second_pass_match";s:39:"!\[center:$uid\](.*?)\[/center:$uid\]!s";s:19:"second_pass_replace";s:30:"<div align="center">${1}</div>";}}