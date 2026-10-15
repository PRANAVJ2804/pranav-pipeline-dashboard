#!/usr/bin/env python3
"""Embed src/monkey-bot/bot.js into app/index.php between <!--MONKEY--> and <!--/MONKEY-->.
Run from the repo root after editing the bot:  python3 tools/inject_bot.py"""
import re, pathlib
root = pathlib.Path(__file__).resolve().parent.parent
page = root / 'app' / 'index.php'
bot = (root / 'src' / 'monkey-bot' / 'bot.js').read_text()
s = page.read_text()
s = re.sub(r'<!--MONKEY-->.*?<!--/MONKEY-->\n', '', s, flags=re.S)
tail = '</body></html>'
i = s.rindex(tail)
s = s[:i] + '<!--MONKEY-->\n<script>\n' + bot + '\n</script>\n<!--/MONKEY-->\n' + s[i:]
page.write_text(s)
print('Injected bot.js into app/index.php')
