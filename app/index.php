<?php
define('APP_OK', true);
require __DIR__ . '/lib.php';

header('Cache-Control: no-store');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; frame-ancestors 'none'");

function h($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function page_start($title) {
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8">'
        . '<meta name="viewport" content="width=device-width, initial-scale=1">'
        . '<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2064%2064%22%3E%3Cdefs%3E%3ClinearGradient%20id%3D%22g%22%20x1%3D%220%22%20y1%3D%220%22%20x2%3D%221%22%20y2%3D%221%22%3E%3Cstop%20offset%3D%220%22%20stop-color%3D%22%233cdfd3%22%2F%3E%3Cstop%20offset%3D%221%22%20stop-color%3D%22%230f6f68%22%2F%3E%3C%2FlinearGradient%3E%3ClinearGradient%20id%3D%22b%22%20x1%3D%220%22%20y1%3D%220%22%20x2%3D%220%22%20y2%3D%221%22%3E%3Cstop%20offset%3D%220%22%20stop-color%3D%22%23ffffff%22%2F%3E%3Cstop%20offset%3D%221%22%20stop-color%3D%22%23c9f3ef%22%2F%3E%3C%2FlinearGradient%3E%3ClinearGradient%20id%3D%22p%22%20x1%3D%220%22%20y1%3D%220%22%20x2%3D%220%22%20y2%3D%221%22%3E%3Cstop%20offset%3D%220%22%20stop-color%3D%22%23faceac%22%2F%3E%3Cstop%20offset%3D%221%22%20stop-color%3D%22%23e07b2c%22%2F%3E%3C%2FlinearGradient%3E%3C%2Fdefs%3E%3Crect%20width%3D%2264%22%20height%3D%2264%22%20rx%3D%2215%22%20fill%3D%22url%28%23g%29%22%2F%3E%3Crect%20x%3D%2211%22%20y%3D%2233%22%20width%3D%228%22%20height%3D%2217%22%20rx%3D%223%22%20fill%3D%22url%28%23b%29%22%2F%3E%3Crect%20x%3D%2222%22%20y%3D%2222%22%20width%3D%228%22%20height%3D%2228%22%20rx%3D%223%22%20fill%3D%22url%28%23b%29%22%2F%3E%3Crect%20x%3D%2233%22%20y%3D%2228%22%20width%3D%228%22%20height%3D%2222%22%20rx%3D%223%22%20fill%3D%22url%28%23b%29%22%2F%3E%3Crect%20x%3D%2244%22%20y%3D%2213%22%20width%3D%229%22%20height%3D%2237%22%20rx%3D%223%22%20fill%3D%22url%28%23p%29%22%2F%3E%3C%2Fsvg%3E">'
        . '<link rel="icon" type="image/png" sizes="32x32" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAE5UlEQVR4nLRXQWhdRRS97/2X/xU10RjbgChtTRNaWsFVq12IuOmmiLqxaamiiEVUcN+Fy24UBQkoWZiNXVpxYRWhIlFrFY1tkP4aKlJMrEkVNG3//2/mXu+dN/P+e2/m/zal/eFl3szcuXPvufeemZdA5bdz/uRz3OwmVNuAYAJRJ9wCac3/CBDRtKRti3a81C/JKx5sEuJpQjp25qn9M8X9IvfyyMLP6zBtTwPSHpJNUJQh60Ao98n2ZZy8ebB9yvtFefMcracDz5/et++f3ICHmz8+Tkp9yNLrnGddT6/b88J62V8X15/nZ/Ls5AuzsRhAWr1oNi9abDzRhX7FQ288M4KKm1s59PXcxzKvGAR2/PL9MxHhka7nvTxZq+dFfT36SHuTCPGgxBGCHlU9px6ehxDBq+gTq/FgwptvkATyLa96HkICDBJRYH4oItgzlMBtLHTqioav/mtbpEr6NiT8MioG9La4X8wrxtqqkYx/fzSG8QGCSNJ8sAavYQ2++LdTQgw0jsbcaVA3JkZZsV/yDLs50Hs+W7854s10B7i0AVUbxmvak2e5RnKzYk68KTmSkRexu7Quk0/WGnN/3tV5JYfY84j/eMS0qDkWFOfybn3Sz3OoeL7//k3w8thW49BUcx5mFs70Rkh1Mh2cBFlI60ZdVS4OxxyzGFVifmDjONTjGjRqCRzYNBHMATRkhIxAi8c6WSvGqLQkj1Y+WUvMBwfqoAx+BHc2GhBmygICLgUMmjUeT0rIYjEHrqXOtbT2ACGreH39Fnhn12OwfXgETl1chle//Bz+vLRqPM9zgMOAqs4LGh6jxo7zqxxeol3bT/kRI5RtRe7JjZthy113m7EtbMTTYxM5ApKI4FpzKvtIJb083zV8Dzw4NAxzf6/A7F9LZl5DFiLxyFApiiEa0sK4dvpk00hEbBUo5fOIVEEo5o+OrIc3H9qZXxpePzkLxxfPg0IbVWE7yW4bltSkd5bh2iImPOB+hmi18k5JyYU4xHBjtw9Cmyc73Jd27I4hs1gSUJEgwchaJeKxhKZDWavt5URgFyMM/JrfdZpXV5Exk1C2p2ZzBJfGys7L5s7TDAhtIE7dOLjc4GDpLhNSducIMmgSYjhtPY8siWiLjCawsba2YRcBsIzncsAwoV1vqgbTCm9k+yYhblcWfuepIm2rADNlZEvR5UARAfDPAqMq0iW+yREInecZAjrPdm0ZLueByJKLjbmUYF4FlkFLCAgPSK1E5N03EiD/PNe8WeqUlnKAukGF7ABCm4Au3dFDICMjqHEORD4PxKHz/MTSIixdvmSgXby8Ct9yCcr8Z+d+hSucTC1+Pl1oGvlvfv8NLjDzSeJK+/W5BQPzR9G90OHaF0a8oGtwnEaC941o68dHTvDAjvze7iy8pu+Cbj1T4LsA+q4XhPG7mCGcC1mGlp79m0/gZpPziF/nvW9aogfnYoqiad5Mh081XeIHDIyHeAQD454ckuaUeC9uPrH3Bx6crp7n/e58xfM85HkRqTCyJizvLh9+66fsyyhtHeKRlf4edU/N8G24nANXQeqP2q2dQ7K3MeDs5EsrLWyP88TRGxNzDMdcylXjJ9iCbctvTK0CFL6O3e+BmalnWW43C27nRROsJKnEzsv2koeVauJ5PkJ0M0KaZ+iPXTz89gfF/f4HAAD//0QXFVIAAAAGSURBVAMAuUJGBupGfmYAAAAASUVORK5CYII=">'
        . '<link rel="apple-touch-icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAALQAAAC0CAYAAAA9zQYyAAAQAElEQVR4nOx9e9QlV1Xn79zv9jOd/joP6Q7p7iQknY6JY0AUjMggrFkuZxQEhID4gIwwvsYl/jGCyxknLkcQZ8ljGBVGHEBHJEENwozLmaXig0EGIhANeUJISEg6kHc66df31Z6qe6vO2XufR5163O5OuJX0d+6pXfuc/Ttn79/ZdW599U1xEhzPvu6Tu2linj4x2FVQsXOCyU4Q7SKDnVTWDaoSO0z5gcoPpvyvKlHVi7I+Oz+rzuX1daivc+erehHQ19fN20NTF/quXl1IRRHQr9uHtNfZX7cv7Pevm9tfBPSPP34QHipP3VueuBcTHEBB987qoAOG6CvTteL6f/yBH74LJ/gwOEHHZf947UU0Wf/+0oKXlGP2rHJITTOI1eiJQgwy2fPN59o3rBPZM9yp2CS5D/PPvr68Tjup1K/+r50hIJtJ577P9MUFzpkgfuThB3NmK+uAv7FP6Cv8YEHk6dsgq0byU+Wl16yv0J/e8sJX3YQTcBw/hyYyl33+2m8rDL14ArykILrIYybUg9rGTAgzznBm0syaYmbNuLnMHNIfwsw0In6fmaP4uTyEH3TThMw1a0VxzS0v/aFrSyHhOBwLd+jL7vzEluKR6U+VHb2+rO6en9VO01wdYCZ3dZyZYsxcMYtQCzFLgpmr1qgJspS+tp/pI4OZY/hBLfoZ+Hn7nZlZ2x/Br2W+/l1l5e0H1zf+1l2XX34ICzwW5tDf9bGPTY/s2vZjJZhfKjt5KjVjHWIWII+ZYzmj0D9Zc+axmfmJds8w078bRfHLu87Y89//+vnPX8MCjvEdukwtvv2GT78cE/Mr5ecL5+esEC7nNIpZWEEncc6MFDPNf9Rz387MED/y8AMBZh2bmXNy5gR+pFemUnBLKfr3N7/sR/5o7FRkVIf+9hs//V2lzW8tJ+MZcjkPM042MwEZ+suc+aTGz/Xcddeug37h1pe9+i8w0jGKQ198/fUbt68cfndp5Wvc8g90zhlpYM4s9BFnJoowMzJzRp9xIvrohl8way7+mD56MHOXnBn98Wt94D0rtOWnb7j88qMYeEww8Pi26z+1a3V66O9mzmzcoDpf0xHcRJFjFitP6sucMaxvmD616MPqOftcCcYsntz2zxkSSj+BP2Z/Uj+GH6r9DPwYiB8af6B9r//E/BNeu0aP/803/vHvn4WBxyCGvuzGTz2vNPODpUG7osxM8JmjS85I8CM/xMwmwky03GduZ2aW3nj62v4AfvRkZqVfHvesF/TSL7zyik+i59Gbocsbv58vR/EvZ84smFVFLjKZmTMTGPOB8pg5xgyAyPmaI63PmA/UwmxQ9ndgphh+Tx+KGTvgpxxmDuBn17XiRwA/UvjD+qXorNIh/2bfB9/3b9Dz6MzQ1b4yDm74vfLjy0TkO1vhRaY6L5jJXR1nphgzV84m1CLMQhFmxnKfWfYfwa9lXfFD/MjFf9Xjk21XdN237sTQz77hs/vo4Ib/V8J4mWUmHIecOaaPVM6smR32vJBjrJyZBuTMJo3fJPAPYmb4zBxdGdATf2T+0Ir/FVuLg39/3h+85xx0ODo59MSs/2rZ5z/TzMcZwigwjfFOzrd2YMFLfRn5xJiJT7IbXBfhfJCT9qnJs3K+xQRtHwL9N+3X9hkZLD6+FvwGklnhcIIHYQx/sH+FHwPxowt+48+vWlmi+Iku3TDBW9DhyHboco/5yrJ4OXnMLAeF32DEc14e2QjotzDTInNmohZmOwly5mxmjuDHQPwI4EcKf0ofAX1e4hUXfuB334jMw+RcdNlN1768bPlqzRzRnEmdhx6s5qSn35IzkmQO1tHcKmrZzUBmzujZH9NHN/yCmXLxx/QRxy/aRnf8ED+64/f0tf0x/dBuzqwoDNGrbvnh112FlqOVob/j+k89vWzx/T4zY3E5c4qZkcFMLEyjzGeZKSC3/Tf6y33mKDN3mX92XXj8dP8W/6QweO8Ff/ieZ6DlSDL0c2/9zDccO7b+D+XHPVFmJmC5z9zCTCn8QISZMvFnMbMLBl9f2x/AjyHMzPV74nf+dSc2rjzzC5f/668hckQZutqeW1srrkHlzIJZHQjX2XKfuRN+Tx8BZsrETznMfHLsMwv8yMBvPPv30JG1P3nmu9+9AZEjnnI8uuHHy0aeY5RxTSyJu1nm3M2F4tFQ4cRQ+vpu29T60tkAfTdv/P6J998MTexuHsq+yN24CeCnCH5S+D193T/a8ZsYftY/uH0Kf2w3A1wfkfFL4Eeof2ZfG34w/EjYp/CX1e98+BT8NCJH0KGf+cVrV8sm3rDcZ2b2EQ3ImU0av0ngpyHMrO1P5czoiT8yf+iAX+iD6YfxE5mf3331W7cgcAQdesNR+rlSd5eMTKjIlMY7+XKfOYnfMPwqiEBjMfNA/OiCP7KyYSB+xPGXJ87afOiUn0Xg8By6YudS5/XLfeYMZkYmfk8/xEy6/RxmjuDHE2efORs/VPugN5z73vfugDo8h67YuWxstVFO5swUYCarx+SgvJyR0pGZ7h/pnFHoA4vNmRHpP4Q/1n8OM/fEjx74kep/pJw5hd/vf8d0cvj1UIdw6IqdS63Xx5lluc9MlJMzd8EP1X4OMw/ED40/0L7Xf8b8s+vyV6YaB7rjJ0x+9tz3vk2wtHDoDUfoF8pilSyxqshsGkeAmWPMpIxxchX5OZEpmEPZF72b75sz1/qtzDhCzkxdmVnhH5wzY8A9A/JXpi45s8nBX+yYYov4Wtw6dPVrVOUVPwk1KMt95h74PX3ef0f8lMPMT5p95m74yxMFip8oK1ZkHXr7yqHvKPvansyZQ8yMQGQK/ZZ9VnZeMpPx+yfNDImcVNgPLDZnjvWPdvyDmBl4su0zu/Yz8M/HafWC9/32s2sJSzmo+J5ezIQYMyEembx9xCJTM7ucFFJBYZkpqi+diqhDzhhbWdh1ve4Z+jKT138Lfk+/C/7I/KEDfqEPpj8AP5xTF8CLG7l16LLxF0NNElduelnuMy/3mbkz9caPgfilXdKhqxcnFgXth7p4uc+cid/T5/0H9LOZKYIfA/EjgB8p/Cl9BPR74EcGM5so/v3nvvedF1XVmUMXk+LFWTkjRSITlJczUmZkBvtHOmcU+sBic2ZE+g/hj/Wfw0w98aMHfqT6B07APjNIMzPF8U+KyYylZw5tDL04m5kQY6aMnHG5z6zsj+hjIH5o/IH2vf4z5p9dl78y1TgwED9a8GOedphn3nDtWdNi/Stm+X7m5vJu+MEmw8o64G/sE/oKP3MGX1/bH8APNb6d8HP9nvghV3am0B+/gZofQ8eo2D3ZSMWlpcwkI9PwyHCNzK4D5TEzLShnxhMgZ17uM/fDbxL49fgXhVmhyTdPidZ3NfvSy31mhd/T1/2jHb+J4Wf9g9un8C/3mePzo/DDrO+aUmHsm4/mncbeglmPRdf3M2Pg+4l5UFT1J837mZU+IvgpE7/Q74K/wJnTCc6ZGpy3cYJvWGHOrO0oy/VS/+61ArcdKf8dLfDYegHpP5zMBuCHXDms/Xp+Cod/ArNrWsp2LfeZO+DnzoxFMfNA/Jkr06nllsCvfMMmvGDrCvocVbPvfeAo3nHfIaxp/BiIHwq/gc/M3vxgVwmJduXnzDjxOTN3eiCgfxLkzEjgF0EdwY+B+BHAD9n+0zdN8Mdnb8Tzt9QuVDJf17LixStOm+IDe0/BzhWTxo+Ifdx+k8JPrfipcujy5K5Wzwfl5YyUGZlCHywyEzmj0AcWmzMj0n8ip/f6z2GmnvjRAz+bn91T4F07p9g5aa7jzsrqRZElv3iTwfv2bMHmCUbImU18fjh+Exz/HZOCKoZmzIQYMyGPmZHBTCy0opGXikzbPx88zUyIM3PM/qR+DD9U+znMPBA/NP5A+17/c/uql1O+6cwVbDH1dZVTVtfM/sl6F/meDcAbyvSlN350xE9aHxW2iqHNLiLqljOTnW0xya2RSbHIpPzIFPJUzlzrtzLjCDkzdWVmhX9wzozse4YrVie4dOP8ooZpncPKelf5y7ev4LmnTOd2mI74EcmZk/jZyjZvZ9ekFK7Gc2Z9twur7jEzRSITA3NmZOSMwn46/jmzGcrMAfzsulb8COBHGP/LT6lHtUfOnFNWTp2N3yTwR8ef63sr845Jw8B8kABSWzsRZgJnXs1MBj4zhyLrZMiZY/2jHf8gZgaO5z7zlrLcWfqbzY0XUD5to4G3MsfwE+LjjwxmJg//pklWzgx3gccsSDGj6xTKKFJBkYxMayB3nrycsRm0sP3uul73DDieOTN64nfzt6fca57UN3YoFlNWN5wrJgM/mFNz/IisnJ5+GP+UXyQiR3V+3HLm5T5zO350we/6OdW4fhZVVg5VrQQH2/AjkjMjtc/MV3aFv564CZTScp9Z9x/Qz2bmCH4sfp9Z4uf6812JRZZR+00KP7Xjr+8tgvhr/Wl9eV7OSJx5DaLMRHIQRWSZkyFnRqT/RE7v9W/Qzsw98aMHfqT6h8U//7+Yt7+wktvH7CcM22em0Mov8U+CkZ9iZmQwEwutaOSlItP2z51HRyYG5Mwh/Rh+qPZzmHkgfmj8gfa9/jNWppk31zlvzaS8rsu+8iB+dMRPWj9jZSrbmxrIwXKNqMiPMZMypumr+WBYZ1mRKeSpnLnWb2XGEXJmOtE5MwbcM0CtTOWPQgZDU4eq95UH8WOEfWbevglnDpPlPnOIGTvgz2LmAH52XSt+IM1MSOXMIdJRuS/qr7Fn9THkzmmj+KPjz/AbE8cfmf/pcp/5ybPPLOyL4Sdy/Xk5b1MfJgfxnDkxP234qQP++rpJkpmaQWlaizEbCy1PjozItNTJnadDZMZWFnadz8wZ9ww4njkzeuKPzB8C+G0O3ZRYUJ33D4lf5LyJ8UNezizk5adp0/lxy5mX+8zt+NEFf2RlQxg/WA69qHKR+8y+/0n8ExzvnJlSzGQvRzszLTBnRi4zR/BjIH60MBPyc2aLv7mumDv1IstF7jMn8ZvZPnSO5yMjZ0U6ZxT6wGJzZkT6T+T0Xv85zNwTP3rgR6p/oPWewbZHbHwWXar54fhNyL7Qyh/BbwL4SexD03KfOZuZB+KHxh9o3+s/Y2Vi14XHr/xc707M94tlHSPJs/Arp48yc0vO7PTn56d8koPMhFjkQEbOcp85jP+47zPH8Nc3ZDNZ7SyFtQgK2CA5uejE2PvMYn7A8Nd6k6Dnm0jkLPeZw/gRwM+ua8UPpJkJA3Jm3j81eiznpUg5RL7AfWYPP6T+NOj5BCz3mYF2ZgZOun1m0T9jZjBndpctpLTz04afOuA3AfwB/5gs95mHMLO2P5OZOuGPrCzogB9u/N3vBNblIups3KPjh3FyZo1/OnrOvNxnbsePLvgjKxv64YfNo6srCjgDxqs7/wnYB+nMTj3AzNQBf31iaiODvYEm5rT6vO20qhfkpQnhN/rATyc8fea0dlJz3+gU09f2yzf67N1yCl6x93yccUcmlQAAEABJREFUv+1UPO2UU7Fr8xY5Hpi3+8CRw7j9sYO47eAj+It77sLfffWecfCTZBxpf0yf4U/qR+45GudvxsPWB8pj+D2cHfF7ObePf/48dGiQY5FlToacGZH+Ezm91//8uqmZ4DXn7cNPXHARNk7kG4RIlPPJOW3T5tm/Z5x+Jn5g79Pw0btux69//nN48OgRSGbuiB+ambn9Q3Jm2f/sZ/PIhS1J1QfK66XIty+08kfwmwD+Nv8kpJ6Hlk6jIwsssjy5gZ1W5zxQ+hBGawZCMmcO6cdyZqj2nf2XrJ6GD172fPzMhZdgg5l4eJuSIuer4/vOPgcfef6/xL966l5pH/T1uTkzmH6XewYK9K93m0yd49KsNHWJSNlXzqbf2YfF5Mz62ZBJPHIgIyd6N983Z671jYrMWPutzAx03Wf+Fzufin2nbp/Xq7kWpcKbkK9u2IhXnndBHD8w4J4B+StT6p5hdl4+SEQtDxr1lQdXdij8OTkztP0Q8+H0HYNPROQwahaRb558+8zfvHp6mWpcOL+NMfPnamAsMcyctnrLcK780tPOxCvOvSCMH0gzk4c/pY+AfgS/4e1r0irmvijqNKLc+UkQP2XiR87K7sqJBBmKrJMhZ471H7LPRPqXuxkvOnsvmoM1W6cX/vkc+Q+VuXg2fjD8HjOjHT8Y/hizUwg/sYfx61I/vD9QHu6/Bb8J4A/5h4ngr/UnTaducpXnQzNzIDJs6PDB6xCZLPTCkWfgR2YsZ9bth/vfd+pqPXY0Wrm33CHZPLux7II/srKgA36hD6avc1K4XJci5Shyay6SK1OfnJmPn4GnP+FYZWTFmLlvzhxg5iCzLH6fubpmljsLpzSD61W7F25fRfbKBG2fJJFB+BHC35TFQkvuzLbbnJyZuufMYPNcado/vCk9P5Ez0xMgZ04wc1Wcs3UbNpVMSrUdVF8/Rv3C7ae1MxMWlDOn8Fv9JvddXBnETxn4HRHnz78Kimkzig3jfj08z3ykWJvd0Am7VIme8qO0HsbPJ8NbmUbKmVP4m+tngcdXlgWUtv8EfhPAn/QPhd+E5Pp5aOjIGpIzY0DOHNKP5cxQ7ceZWQxq3b7bipP1vvJuzNTlniHGzLDOlIPf5byzdQXRXHiAfPbGsaZ/vrKMsM+c1jdsH/pJuM+smdnpg5UGmlGH1SGZqRU/8lemGH5wZnO5ZQx/c2Nn+7EvXBxHPjcjwMx6fqDtZ/ibFRAMP5GXM9uVrdaLPA+tI59FBlin7nIMYiahj0xmSkR+gpmt/WKZVDd4xvSWB5kFC8qZDW+/Hf+sVf4KXFL1seSt88/kyFnZlX8hxuzB93LAZxbFRD6zsMgMMRNFmNnTjzATJZipFzNzZ3RlAWVvDznx8aEYM6MdPxBhZtV+DH+o/6K2lnRQjVva+eX9mwD+kH+YCH4TwQ8I/1TPQw/JmWlAzsxypgCz9Nln9uwD1ODPW+RlpVEEzneRR+1Dilla8At9MP1u+OfXFbXRc8ZeSN3OW4+cmY+fQUA/jd9/P/STYJ85xsxcn/gsN3qWCZrZ7y4/sfvMGSvT7HMxv7BOfceu82DOxh9iZhGkc80o/noepg2FPtGfZw7/hVK5XBvo9l1ZqHp/OQL2x8aP4a/t23PKNpxV7pOzCy3++w4fwhcffjCMn7dv8UOSkqG53my+ColjzDrHyfHr+Qng9+af4bekoYOG4Xf70KJRYLE5M3y5UZHp6fdhZq0PuN2cRZWh/oFUzvySc87Hq/dfgot2nA59NHbz40uPPIRrbrsV77r+c6z9ODNDzOvic+jZO+/MxMef9A82/yCRfkA4O/NPOGZu5FPBNOCRpJkl4PQpZqWuzBxKZ/oyM9TKInG54GBBAZIMM0iOjJWJsHXDBvzHb3k2vv/cC+R4t6wM523fgZ+79FvxrJ1n4d99/GO4/8jhdvw1szkvd9fXNyne0Vfu+mH4k8zKcBcR/7E5c2JlMrOn7fgkuEGo5yjgZCryW5lxhJyZujIzsw++8/qMMj+Kul5ggNykVjaH/5LTzsA13/1CvKhk54JNTlXqdmLy5+w6Gx/5vh+Yla34m3Fj/5ydhTg/VC6C3Pav5t8w+yiVM8tg0DmzZG79fmi4gzsvdxqITlWE2OlVDC/0EdDn/Yf0TaJ/tNjPgooBmj3PDOak1jldvY8cpING2Vfjf9nT9mHPtlPF89Zk5DeS+nnskPz0zVvwqv0Xh/HrlRPzHBpeCaT//mBXuQz2EP7o/JOafwTkifmfiJyEYszCI79mTrVccBAgnTMH5EZFJlhkBvvvw8xA7BtQNDeMqpxPO3rLrX0J/M/Z+VRcfv5+qBjoXb5g9zn43nPP9/GzcXL9k/qSZPwSlMiZQ8wMysuZY8xs9cGfh5ZO5TMLxZmZhV448gz8yJTpTFh/KDPDY2YdPOFygBwhZpH4XzL7da35lVQz/dD6j3zjN0n84E4Dy3yo33gULjGSPI0fbN59ZgbTb5l/vfLD24dO5cwBZhZBAIBGypkHMzOh7XluPbij1hHIGRn+C1dPs26vvK53fV+5Q1I9C1xAOgO3DzVDw46XLilyvpvc9Q+vf8HMYtzmM2fnD2r+Q8xs9WENCTwPfRLkzNnM7Lg5rS8HtxlUqomjGZRR6hTJGetZr57DPufUVWYPxKT0rW9emeLc1dUE/rkzL+pvfPMyiD82/6TmHxk5s/ZPNv/N7+4HmLG5NsDMqhGQzpnhy42KTE8fPZhZ6wNZz3MDDJ9bLceoR+8Zavn+eq+ZIFfpMer7d5yBtqcmZ6MXerCIVC48RJ7AP3Sf2WdmI/QnznmQEVm5OXNIP5YzQ7Wfw8zuSEa+Cds3c4LarYuR63blCI4fcHhtbV6vZ8nqjVA/sr7eit/mvLbunm/GSPIhzzPHmJcIwZzZOnetH3g/tLPNZ7ZQ5I2QM9Pic2bJzHU/IDboI9Wj+J1cP7U3dp1c9Afwk3QGiIER9b5yct4HwcyN8zXzD7ayQ81/KzOj1ocgw4nv+XT8c+YF7DNz543rNyuEGbeumcnDP16awevGMPzB8a9zXVpwmZp/UvOPjJwZyr8AxJh9+mTdZxaRDSg53KCNXYr+JTOLcTaLKNX8hPDP/i/m4y1KsJVmmNz1z/CbxPxwUggxs9VHYHylc08lnZsBz2bUnY70bMauzVtx9pats99P088mHDx2DDfap860/QWCz6Yop+bLou2AMRx6yoPMYpgzVA6VbGdA2ZAMnx/2VtnZj5pBnV7hnKiej8FyPu5FZP6tnelnM7g/QfkTzxya+ZyCRQiIjk/OHGHm7zjjKfiZfZfggm3bsWlFvgk0dNz1+EH85YGv4D9//nOs/1jODBf5zLk0owyt594zNPaOWerlNzQ/s0GYE6u7oIBk0IFyImBR+8xObiDJad7xBKJT3/OxiJwZMuedTib4uQu/Cb/9rc/Bxdt3YGPpzJWcINslVZ69dRt+9LwLcdU//27srp4hDvYvJ7UZbLFbMGY9A39zdiFlCv+M8UJbb+PWRf+KtIbuMwd3Q1jaOQkys2rEMhBymDmR0waYec/WU/B7z34eXn3evpm+dtqqLALOzMtvLIPgj5733fjes/fCMbOJ9y+ccezSRFa2Wm6ab/Ngf+FjrLpYmUygfzsvxWJLULR/R+wut04zswnok+jH+id57+VwyyaCzIoEM8ucEWJZ5+3LyKxeaVs5ZGXc/Omy2lmrf6Kelm8pWf015180a9sxY8Q+O6hSHi67yjUzww5+KNiLEeve/HD7mnGp33AEKrw6RpLHmVnNvxo/b+UHc26ozIEC/mmqp+04MyPCzDQwZyYK5sz7tq3iJy64uHZKCZ74pGXK921fxWsuuIjZZwnA340x9ZgZ414Sg3kp6x3lIfyQOaN4vhkYrV4gMD8N/ub87J9ME5o6VL2vfM7UTc5ssMicWd8zTAQzN16AkXNm4zNzVf+es3ZjZU7ncM8Xz2Hwehf5D5U5NZ9UUoPWUAFPW+ZOKuu95ZCT5q1MTdpR4WickOm7ene5gTvI4tROw5wRtKB6x5wZyr/4/CGdM2v/TLwfmufMAbllZr2MKmYKMDPmNpUMvb12RrjSuGVUPZGYJT9982acsXFToP8m8uf2Nc5ICn+zEvSVu/GTgy2DHIGS59495cn+Q2UsFx4mF/1r+0LMDJLMzPSEfzpFj5mbILHfFCLErHAM4zNzLGfm+mFmbqoXlrmzfSbCMpeq95Dvrx7PDDIzHxxAfKNo6wPlNmbj+HUwNMweC5ZceTPubjYU/lnJ0gSCKA2NJG/Bb6t65W/sN2DMns6Ztf6ED4LwfBYxYWbWg5jPzFX9zOqvSW3cWC+jTfpAo9T3re6IMnOQsYDR6kZMho+/uY7q68gopzUD5BTIma0v1BNnndEvKXK+q/x45sw8SCr9CZDh+coJROT3YObq/JG1tTDTjFAe9Z46gwg+o5gOSn9oPYgfzPl1sI1Vr1eM5MpEdWg0ue8C6un+MWifOcbMTfCw90MvZp+5tjLxDaThszJKKSabrxy8/+ZG1cy/FGleSiPqPeRu8BV+1n/B5LMveepxLWy9n5w7Uwz//GUztVkFCYYct76YfeYgMzO9xPuhEWTWJkJgmRlMv52Z+bJItTNUn2clq5Oqd5Ej2H9jn7N3vuU2x6HLvvIc/HayYeQ9gK33kweZEZLZ+NbbjM8TW3N95UHmJYyyzxzXn+tNT+TzzM11dtdS1dFTPv9pmP3+ymAfEOKDourD5WH81vnGrjPnaSbOZz5ydvPgZyM3VN4wdZqZ59c7eb+cuTkvGHpR+8xQnRm4o8k5+Xstxqz7OTOY/WxwAJm1DKzn4o/b31+O4PgHVibQYssUfmEHMDRndsNONUNrZqkbHWOf2WdGuPbhvgzQX98WA+VeCY0Pwp5xS+MzU2hlMkaWMOHzneRN/6F7BubM9ZImSrAgHSgP4jf+/HNnB7sOlMvMOudm3xT6zBzLmbnz9GNmbnzsd+SKgXLPPm0/1O4I06OB8jb81HxTyUs45+wvl3bolXNWxv52N7X8be9Ocku0CDJrbSd3alDAv6LMbN1V+h+C74dmkyEmaTxmbpYZmR5IZxtSlzmmkfj4edjRhH2klNX7ylP49XjnnM+Ve8ysnYT9gquvX11XJNrPl4v+OTNj/Jx5ftY6XvU8NAI5F2eWQOQgl5kdN4X0qb5eMN0I9XDOLO0rWB08uEaoR1cmNc52MhA430NOYnwD+Jv+iQJlETnfXb7IfebaXVX7Tl+9HzqUM9fyxphOzKz1Afk7gAAb89FK337AezbF2LEYtZ5cmQhi37p5Va6t67KjXIy/CeBvnG8xw+5KPj58ZScKMGszPnn7zFIf0Ls5kyCz2tCQd8eDcmbymZ8/z1yoOg2Qu+E1kf4NBGONXAbxQ6YBzStxY/vcfeTebhOM6r9yaJXzsjpGknvManPm4fvMUeav8U8Wuc8smd1n/qYs4NdpgJxAgf4hgtMFq5+TGfEAABAASURBVPqyRNT7yCP4mTz4PDMwkpzND3j/jV3EbtwKUYeq95V7/ZPET9bh+ubMQGyffRJiTj9yhjJzKGea97SIfWhCwr5m0MB3J4z7JYIGf285xKQF8XN7IYOUv5Gpq7zu2fUPn9ns+aKoCZXXaTw5I5fxc2ZE/XO6yH3mmZaJ5+Ty2QQ3+f6zCt3kM/ugcmbVP4t15wSq3ltOPGdGcJKhgmOUks+PYD7WPxEIvlPUZgscfeUCv8bdiZnbc2Yrr/1MvR+6726GO6Q+4pFZR0UBaazcV+4nb5iTL3dQg0ak7R25DjmpDX7562TNbs+4dcTwzyk1Wo6WU5sIfuQys50u6T8AkpkDxD70mMxMyHnXXMEjT4EZVuc5o+7fjm1dGhfhUPVe8qb/dM5og4vN+tC6ZbYQ/vmAz0/W9rrct7Zf1fvKORnG8KeZOcTsaWZujvDz0MhlZsfNaX0jBrfRpwWVvB/LzKFl0viDIuo95W05I3fGUUsH18dPCj9zvrHri9xnjjFzI2fPQ6MHM1tr6vOJnFnoQ7U/bhnsvxnb2aDNz4eeCRlcRu8Z5Mqkn28er0zjn81eZJxGL3NzZgowL5CVMzcHey8HrBELzZm1UQjsIyO+79xFXlOyx0yyf9Q57fwoVL2vPAd/ZVhjp92lEfV+cv53FEMrk6l+6Y/U88wU/7WqvvI4s3L/YP7jzEWMecPM7N8zBN4Pvbic2anJ64om54Sq95T7KwM8OfFB03bB4eslJ8ZMAfziWRMzT22NYfW+8lD/YPhnDje3QMwzmifKZdlX3n03A+jHzL58EvT8zswcYCZ2XUx/xqj1hcWYdc1Mon+fOUYvEytTc4h9dNOyz54pZ/Ci/cf/rmCo7Clf4D6zJ4e8p5sucp85xsyNXP+tkiZ4Que7yInCOTOJC410PrFbMUBu8YVXJoIe78gNZ285fGauGbN52i4ajPZ3AofJPfwqyELMCnRhZiC4zw72TWHjDd2YWUVWBjO5SYBkUracEqv3kYftZ/2qQSLllATTXy5y1jB+Z//8emKTOYY82H8z4PZ3AOt/rD7jyzHkwf6V/2AoM5Ng5qb9aT9mpl45MxonwxysfB5aRrA2vou8YP2480bYL5dpac+QusdMenwQGJeR6h4zzz8w/M2+sf2tTFEnVe8rb2dmeMyaz8xKDjeflf4kzczGGzyiSM5MGkyM2V37tKAyzMzSfvGljnG5qKj3kafwq3uLRZU+83H8qRx4nPoi95mlvoFm/mlz1aL3mTkzS31jjcZI9SJqP8ndHDA1kY5gmByQzKScrCDG6MaMVrdkA4r2PxuFJhdeVElp/CTIIJeZwzlzw8x1EXkeGu5IRn4yZ1aRRX5kyvdqYLYgiXpPuWRmWPDyRk1H/nh1n5kgmGXO6PPJLEatI858NX73u4A856W6ARpF7uE3iDAzWphZ248oM8/1o++HRnM1Y7YhOTMUM0MYKx44AtyXF33ljiKc/fZ6tjsAUukDr/eUI7QyQTKLmozCm5y+cjBmnPdsnzqcyXkOTczO2ilVva/cMatjzn7MHGN28phZfFPYzsz99pmhwBh2ork7t998Ea/rfdeucnlDFPsGlMCdAKreTw7O3PW9hZ6UmZ1wJSB3b/rK25nNObX8+4KqPoq8Cfo+zCydP4eZG/1pmpkTOTMfJCAsBwWZuZEXAXBjlMI+xljcDhLU2QBg9b7yaP8Ovx3vYOka7CovYvPT9EtUO12qfQyWV+FlsAKfWTF6zqx3cyZpZobPzDG50DdxZubOx5i6MXbMevrZFFY3TJ8iZRc5EGYmbp91Mqj6EDkrQ/htScHSRM53l0fwAx2YOS9nlvqmeT+09UbGrMP3mYPMXJ9//Ngx3HXwUet8sxs6olHqNz9wH4tsf5/9q48dxMNHjih8ZpT6LfffJybTjfUc/x0PPTj7A/azB6uYc45Rv/mr97rx13aWJ26YnDZPUQrljOIbxGHyO8xWPEIbAsyay8xKbvWZf5LPzM0ET6CcsokIn5nB5Igwc9MZgswk2wduf+RhxjBscgbUj6yt445Zu7H+5/Z96aEH0OR4Lg0YVq+C5CuPPoLUylSUuwCVU0eZv2f93kcfxYOHHlfMJvs/bFZwpzll7iLiD/yMV/8nrAaYebx95hgzN/45aWa5Ydxx95nn13mRWbfwF1++XTnl8PITd985e5m6x8yq//9z2xehf8F0aPm3t99WT3Ia/5/fctNcr3HyEcq/uvVmh6/u1zGbw//hjedBfCnCnXKE+p+t7BbOueicuZngRi/xfmjZqc/M7ry93NNXkQWXW1f615fL8/+9+65ZS2M8D/3I0SN4/z99LtK/tO/TZb/X3XuA3ZhCOEnX+tceewxXlX0nV6Z6PD/2xVtxy31fm02j/MNHrA5ky7/y8MP40HWfQShnFvhLwdUbzscdk23WSebXkQPE6l3llTNfa86I9E+j7DOn9U39V7AwNGdGa87MmZm3/4Gbrsfja8fqSZKMZ5fpCCNq+QdvvB6PHj1qIxgEpPbZ3/e5f8BauVTO0hXTjJGRdZgs+f+47h/weNm3G+sIM9XX/86n/h7r9S+UikdeDfOdut4mf88nP461+s9w8JzZW5nKsijt+o3Nz5hrJ7406Sp/vNzVeMfKxY1bZDJzaHyQycym1pfMPimVH17UPrPTl8zM9e87dAgfuuXG4DJKSC+zXP6FBx/AX9VLfoqZuf13H3wE19x0gxtkowbdOlFa/vnyZuzjt38pe2WqjltLhv6zsm/9vHMTLLHnobX8k3d8CZ+9607EV0Y9P8C106fgf033CqccWr57uh8Pm43IY2buvH1zZs7M9vqHzcUfvbpM6Gg/WGd80i0zc1nAGHuCfZD67ALGMI3+1ukGvPKiS/CCvedCHnxt8Ot3ljdh7/7Mp3FbeaMl7Sc3mbMlUQCDs5+wbeMm/Oil34Lnen2njy8+cD/e9alP4I4HH4RtkH3QKxvIx3/6lq147bMvw7P2nIMux41luvRf/+6vcaC+sbbtswEX+AGAX1ceO4vH8MbHr8Wz1g6wlu2aq0pfXv38yPRcvHPDJThoNrhLqAV/bYtyxoC+tj+mb09eVzr0VX9dnnyeZZ7qu3gjadx4ubTxIsfpk3xQhgqmX9dhoHOmhvG2TTdi/xlnYN+O03Hu6o75XWu9fDXM+MDhQ7PtsVsevB93PvyQXI4KCtjPfIlc+qGXqx1btuDCM87ERWc+BXtWV8t8bCKdxMx3E6r896by3z1sN4Uztxi3Ig9/5dgX7dyJ/U95CvaW2NGkge73q3Cg7PvGe+/Bzffei7sfeciNr1qOxfwUAfzqurNKx/7mta/h0vX7sWf94XLMjWRWuyIZrJXFF1Z24LrJGfjHlTPwIDZ2wA+PsfX8+/qkvhzj/inxl5f9b3PJR696X1l5tQyQGDPDxkt/Zub6NtSUvmbWWsNbZqzB4Ea2MzMbRNlhC37O7Ao/M6gXfqEfYiZjrw3iz2FmoZ+JX88PBfDr+Ukyc8B/1HVh/NwEj5mby94/KQPgwKL2mUM5s6+PgD7vv4lM3X6s/3jO7DGYVUjt5nB9IyZD9E8Z+A0C+gjoq/YR2w0I9Y9Azgml34LfKPusfgQ/jt8+cxB/XS93OA5MJ6ADehDml8gItsu0GrwmMnkn/m5GfZfq6av22XVBOfTyw9sP9Q+//eizKWo3x4Tsc2UrfhPA7+kn8Cf7r+fHAodvn0nYx5y7afB4vTcj3j/DTwo/cvEXB+YMrYzJY1bKYKYY8zmqCsu5Piz4Qe8NCTJbgpk1cyBjZeq6z+rpo0U/wXyEOLMarg/EmTWlH2JmNX/HYZ85hR80OTClCR1AIVIoeMwH9N5nlswKX24gmcnKjdRn54lYZAb7h9+/mjzLDMl9dnfe6MFr2WduBlDaD79/MPzEg9TU+iaNX/fPxhnKiYL4kWBmcPtkcJ+ofebUylTezR6YHD208tmycjTKbJTBTFhQzmzkZIj+KYOZo/odcmbqszLJyQ/ij/VPKWbLwK9IYzE5s7L/OO0zh/ufx1b59djRYnrkM5PbLr/84VLp7/lFgtlizMwnw2Nmx7iDcmaiEXLmWP8ZzEwd8JsAflLMRBFm8vQ1swFJZoZiZtG+dG50YmbNrMDJlTOL6z72wJXvfGS+zUv4c4hJ7cJMAWYF4jkjjyzePly/Tn+MnBmSmRBhBk8/Y2XqkzO3MlMH/DFmNRH8yGVmjMDMAfzIwU9x/Ijjp9qHZw69toIPNyhamdkoZgBnTsbszgYpN8wZBTObgH6Amb3+XT9SPzNn1PZBTqYb6wgz5eI3TE6pnBEnYc5MHZhZjw86MnN+zszxl3rOoW954atuKoU3Q4GJMhMWlDOryQj3v+CcGbkrUwC/QUAfAX20MFOqf3zd7jNH8Rtz8/1v/o3ZM7n2Af8y7fjwcp9ZMUsKvwng9/QT+JP9z/td7jNn4i/WP9zYYR26lH64OzPFmM9RVVjO9WHBL/eZ83NGYb/h+j1yZhPBDzV/XfF7+ovBT8XEOrRhnmEuuuYD1SNrq7CBp5wB4of97AajPunpN4FGTN84o8EjUyjMpZ4+u04Nhq+v7aeAvptMdSKNv24jiF/oM/ttv8Ze2wm/6J7rZ+LX80MB/Hp8hD68MWzX74hf+1cKP3DP/W9+29lNxTG0MdVjtu8KMhMWlDMb3n4PZo7qL/eZs5gZsZwZLcwcw9+XmVP9I3nPUH74PTAXdw6N6hdMN7y5VJ89YMs7WVjOTGPkzLH+Vc7c2n/TvkEQvwngZ9fZ9k0Ev4n0z/p1+oj0H8AP6dyu/5zdjCdozgwbnF+lzetvAjuEQ1dfspQX/RrgIi7IrNCRZeAzk2bWRh9Mf7nPnMQfY1YTwY9cZsYIzBzAjxz8FMePTPy1Phnz5urLFLBDOHR1HH702NvL4l7HjMZnFpXzCLlhziiY2QT0A8zMQCz3mRUzKyfKws+CBCoIJLPmMrMeH3RkZuOPbxYzS/zlcfv2TdvfBXV4Dn37FVccLhWutJEvnFIOho0czWxcrpmdIsyEDGY2Mf0OOTMizOzZF8BvENBHQB8tzJTqH8t95gz8peyXb7/yysNQh+fQ1XFzsfl3yuKfBHNiuc/s6yfwJ/uf97vcZ87ED29+rr//i1/5fQSOoEPj8svXy/zkl3xmVZGZYiaEInOMnDmTmTVzIMLMSDHTcp85id/TXxB+KPwF/gM+9KF1BA6DxLH/6vf/QdnUq9xg1D3ZwFXOaGXW29VgNGouMmdST59dpwbD1+eRyzvh+m4y1YmAvsPVtBHEL/SZ/bZfY69d7jMr/AJnR/yEqx749be/EpFjgsSxbcvh15WtfMZjPs1MXA4WmcZFdC9mjup3yJkpwsxCf7nPHJWjb86cwA8kmJkS+M1ntx4zVyBxJBm6Oi6++v1714g+XTb9FKgICzMzLwLMBNdGOzPXmVZQH/2YWdgfYSbPfna+lZmRxp/DzDH8enwut9lyAAAEQ0lEQVQXyswB/J5+BL+nz04iA79gZtvP1+jY5JkPvPWtdyJxJBm6Om64/NVfLtt7OeYvQEgwaxOZjUkDmBkeGMdMiDCDp7+gnLmVmTrgjzGrieBHLjNH8AMdmDmAHzn4KY4fmfiF/qx6rEDx0jZnro5Wh66OW1/xmr8tu/9FWBAysprlDTbieWQv95mdPvz+jWQw7kRZ+FmQQAUBd44n2j6zvGfAzz/4lv/ycWQcWQ5dHbe88opfKzv5AFEiZ1bGhJlxwTkzBuTMBgF9BPTRwkyp/rvkjBH8UWZ78uwzM3l1E/h2ZB7ZDl0dj55y9HWlOZ+RzAzJbIyxBDMzkFFmnoFd7jOHmfHraJ/ZtVveBE6uQIejk0Pf/cIff/yxbceeW4L6UywsZ85kZs0ciDAzUsy03GdO4vf0F4QfPv6y+NNNWw59511ve9shdDgM+hxEZt8Hf/fNKPAGZ4zFAG7VTGqrZIHY6yAHw9eHZGbGKgg4szoR0AeEEbTcZ27X74hf4OyKn6rHmH/p/re841chvSXr6MTQ9jCGbv3B176xNOOHSyMeX+4zIx8/c1Knv9xnrvspfYleUjrzf0IPZwb6MjQ7LvjD9zzDrNP/LHt/KkSEL/eZKcZMMfzqusUycwC/px/B7+mzk8jAH2b22wuz/tKHfu2dn8WAox9Ds+MLP/jaz05WJt9WWvWJbGaGBCOYCRFm8PQXlDO3MhOY/nKfOZ+ZEei/GU/ziWOTY9861JmrY7BDV8fNP/hjd+/efffzUJjXlijucMzgwDUfmvPLfeblPnNZfrksXvfAltXnPfrm37wfIxwGIx8XX331xqOHH3xdaewvlmN5Fnea2ZgH3rA/H/TCG6z2N7/LSdPOYgxa9OWkxfWNx1hhfaNwxvRb8Ktx6oy/qo/+5vxR8d+DonjT/VtP+2+48sqjGPEY3aGbY/fVb92y6fCWnwKZN5YozqzOaWZloVtXHQOD54zkL7P2BPsg9Vn7Ik1I6XfMmXNzRqIM/R74Fc4oftvcic2Zyx/3lFe8acsR/G7X7bjcY2EO3RwXX/2b244cMj9Zbsb827KzvR6zZP1NF3g5Yx4zMeZDX2bi+pJJ08wEZ/9gZmbO1gf/IGaO/02TbPzAlwui3zzlqHnnohy5ORbu0Pw4/32/9cxyPF4EQy8qq08PMpNghQQzM6qQ+vaHbUNeTgH9jvusTgE5zKTvGaR+gpmt/hBmPhH7zLMz15YfPrJemI8+9Ja3XofjdBxXh+bHhe//7bPX1uiFJejSuekF5RhsCuZ8hZ+7nXQ5s+1vbGZ+QuXMR8riY6X0Iytm/U+++qZ33IsTcJwwhxbH1VevnPfIV3eXA7sHxfqeoiwN0d4y4PeUXxtVn/eUA3UmFEP0yplbmTmkz9rPYWaxsjw59plLHPeVxZ3lpzvL4JmV5fdrXy7ldx4r/z10y5fviv1a1PE8/j8AAAD//5/8LVEAAAAGSURBVAMAZMD3NZ7HLiMAAAAASUVORK5CYII=">'
        . '<title>' . h($title) . '</title><style>'
        . ':root{--bg:#d7ece8;--card:#fff;--text:#1c2024;--muted:#5b6b78;--line:#b4d6d0;--accent:#10978c;--accent-t:#fff;--warn:#fff4e5;--warn-t:#7a4a00}'
        . '@media (prefers-color-scheme:dark){:root{--bg:#14161a;--card:#1d2026;--text:#eceff3;--muted:#98a2b3;--line:#1f2e2d;--accent:#72eee5;--accent-t:#0d1b1a;--warn:#3a2b10;--warn-t:#ffd699}}'
        . '*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font:15px/1.5 system-ui,-apple-system,Segoe UI,Roboto,sans-serif}'
        . '</style></head><body>';
}

try {
    $c = cfg();
    session_boot();
} catch (RuntimeException $e) {
    http_response_code(500);
    page_start('Setup needed');
    echo '<div style="max-width:520px;margin:12vh auto;padding:24px;background:var(--card);border:1px solid var(--line);border-radius:12px">'
        . '<h1 style="font-size:20px;margin:0 0 8px">Setup needed</h1><p style="margin:0;color:var(--muted)">' . h($e->getMessage()) . '</p></div></body></html>';
    exit;
}

// Folder URL of this app (keeps you inside the dashboard and shows the login page)
function self_dir_url() {
    $p = (string)$_SERVER['SCRIPT_NAME'];
    $p = preg_replace('#/index\.php$#', '/', $p);
    return $p === '' ? '/' : $p;
}

// Log out
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    $tok = isset($_POST['csrf']) ? (string)$_POST['csrf'] : '';
    $want = isset($_SESSION['csrf']) ? (string)$_SESSION['csrf'] : '';
    if ($want !== '' && hash_equals($want, $tok)) {
        try { log_event('logout'); } catch (Throwable $e) {}
        $_SESSION = array();
        session_destroy();
    }
    header('Location: ' . self_dir_url());
    exit;
}

// Log in
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $u = isset($_POST['username']) ? (string)$_POST['username'] : '';
    $p = isset($_POST['password']) ? (string)$_POST['password'] : '';
    $who = '';
    foreach ($c['users'] as $un => $pw) {
        if (hash_equals((string)$un, $u)) { $who = password_ok((string)$un, $p) ? (string)$un : ''; }
    }
    if ($who !== '') {
        session_regenerate_id(true);
        $_SESSION['auth'] = true;
        $_SESSION['user'] = $who;
        $_SESSION['welcome'] = 1;
        try { log_event('login'); } catch (Throwable $e) {}
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
        header('Location: ' . self_dir_url());
        exit;
    }
    usleep(1500000);
    $error = 'Wrong username or password.';
}

if (!empty($_SESSION['auth']) && !user_valid()) {
    $_SESSION = array();
}
if (empty($_SESSION['auth'])) {
    page_start('Log in');
    ?>
<style>
html,body{height:100%}
body.lg{margin:0;min-height:100vh;display:grid;place-items:center;padding:16px;box-sizing:border-box;overflow:hidden;background:#cfe8e4;color:#121d1c;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif}
.lg input,.lg button{font-family:inherit}
.lg-bgb{position:fixed;border-radius:50%;filter:blur(70px);opacity:.7;pointer-events:none;animation:lgFloat 16s ease-in-out infinite}
.lg-bgb.a{width:420px;height:420px;left:-120px;top:-100px;background:#cdf9f6}
.lg-bgb.b{width:380px;height:380px;right:-100px;bottom:-120px;background:#fee1ca;animation-delay:-6s}
@keyframes lgFloat{0%,100%{transform:translate(0,0)}50%{transform:translate(40px,-40px)}}
.lg-card{position:relative;z-index:1;display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.04fr);width:min(1060px,100%);height:min(640px,calc(100vh - 32px));min-height:560px;background:#fff;border-radius:28px;overflow:hidden;box-shadow:0 40px 90px rgba(51,117,112,.22),0 8px 20px rgba(51,117,112,.08);animation:lgCard .9s cubic-bezier(.2,.9,.25,1) both}
@keyframes lgCard{from{opacity:0;transform:translateY(30px) scale(.97)}to{opacity:1;transform:none}}
.lg-card.shake{animation:lgCard .9s both,lgShake .5s .9s}
@keyframes lgShake{10%,90%{transform:translateX(-2px)}20%,80%{transform:translateX(4px)}30%,50%,70%{transform:translateX(-8px)}40%,60%{transform:translateX(8px)}}
.lg-left{display:flex;flex-direction:column;justify-content:space-between;padding:38px 56px 30px;min-width:0}
.lg-brand{display:flex;align-items:center;gap:10px;font-weight:800;font-size:17px;letter-spacing:-.2px;animation:lgUp .7s .15s both}
.lg-mark{display:flex;align-items:flex-end;gap:3px;height:26px}
.lg-mark i{display:block;width:6px;border-radius:3px;background:linear-gradient(180deg,#3cdfd3,#17837b);transform-origin:bottom;animation:lgBar 1.5s ease-in-out infinite alternate}
.lg-mark i:nth-child(1){height:11px}.lg-mark i:nth-child(2){height:20px;animation-delay:.15s}.lg-mark i:nth-child(3){height:15px;animation-delay:.3s}.lg-mark i:nth-child(4){height:26px;animation-delay:.45s;background:linear-gradient(180deg,#faceac,#c86e29)}
@keyframes lgBar{from{transform:scaleY(.5)}to{transform:scaleY(1)}}
.lg-form{display:block}
.lg-form h1{margin:0;font-size:40px;line-height:1.08;letter-spacing:-1.2px;font-weight:800;animation:lgUp .7s .25s both}
.lg-wave{display:inline-block;transform-origin:70% 70%;animation:lgWave 2.4s 1.1s ease-in-out infinite}
@keyframes lgWave{0%,60%,100%{transform:rotate(0)}10%{transform:rotate(16deg)}20%{transform:rotate(-10deg)}30%{transform:rotate(16deg)}40%{transform:rotate(-6deg)}50%{transform:rotate(10deg)}}
.lg-form p.tag{margin:10px 0 26px;color:#576a69;font-size:15px;animation:lgUp .7s .35s both}
.mk-say{position:absolute;right:6px;top:-62px;max-width:210px;padding:9px 13px;border-radius:14px;background:#fff;color:#17333a;font-size:13px;font-weight:600;line-height:1.35;box-shadow:0 8px 22px rgba(20,60,70,.18);border:1px solid #cfeeea;z-index:3;opacity:0;transform:translateY(6px) scale(.96);transform-origin:80% 100%;pointer-events:none;animation:mkSayIn .5s 1.1s cubic-bezier(.2,.9,.3,1.2) forwards,mkSayOut .5s 8s ease forwards}
.mk-say:after{content:"";position:absolute;right:46px;bottom:-7px;width:12px;height:12px;background:#fff;border-right:1px solid #cfeeea;border-bottom:1px solid #cfeeea;transform:rotate(45deg)}
.mk-say b{color:#17a597}
@keyframes mkSayIn{to{opacity:1;transform:none}}@keyframes mkSayOut{to{opacity:0;transform:translateY(-4px)}}
@media(max-width:520px){.mk-say{top:-58px;right:0;max-width:170px;font-size:12px}}
@media(prefers-reduced-motion:reduce){.mk-say{animation:mkSayIn 0s 0s forwards,mkSayOut .3s 8s forwards}}
/* monkey mascot */
.lg-form{position:relative}
.mk{position:absolute;right:-4px;top:-14px;width:128px;height:auto;overflow:visible;z-index:2;animation:lgUp .7s .3s both;filter:drop-shadow(0 10px 14px rgba(36,90,87,.22))}
.lg-form h1,.lg-form p.tag{max-width:calc(100% - 124px)}.lg-form h1{font-size:35px}
.mk .body{transform-box:fill-box;transform-origin:50% 100%;animation:mkBob 4.5s ease-in-out infinite}
@keyframes mkBob{0%,100%{transform:translateY(0) rotate(0)}50%{transform:translateY(-2px) rotate(-1.2deg)}}
.mk .eye{transform-box:fill-box;transform-origin:center;animation:mkBlink 5.2s infinite;transition:transform .25s}
.mk #eR{animation-delay:.02s}
@keyframes mkBlink{0%,92%,100%{transform:scaleY(1)}95%{transform:scaleY(.08)}}
.mk .pup{transform:translate(calc(var(--ex,0)*1px),calc(var(--ey,0)*1px));transition:transform .18s ease-out}
.mk .hand{opacity:0;transition:transform .45s cubic-bezier(.3,1.35,.45,1),opacity .2s;transform:translate(0,70px)}
.mk.pw .hand,.mk.pk .hl{opacity:1;transform:translate(0,0)}
.mk.pk .hr{opacity:1;transform:translate(20px,46px) rotate(24deg)}
.mk.pk #eR .pup{--ex:-2;--ey:5}
.mk .hand{transform-box:view-box}
.mk .brow{fill:none;stroke:#5a3118;stroke-width:5;stroke-linecap:round;transition:transform .3s}
.mk .hr{transform-origin:120px 102px}.mk .hl{transform-origin:80px 102px}
.mk.pk .br{transform:translateY(-5px) rotate(-6deg);transform-box:fill-box;transform-origin:center}
.mk .mo{fill:none;stroke:#6b3f22;stroke-width:3.5;stroke-linecap:round}.mk .mo2,.mk .mo3{display:none}
.mk.pk .mo1,.mk.det .mo1{display:none}.mk.pk .mo2{display:block}.mk.det .mo3{display:block}
.mk .hat{opacity:0;transform:translateY(-44px) rotate(-14deg);transform-origin:100px 60px;transition:transform .6s cubic-bezier(.3,1.5,.5,1),opacity .3s}
.mk.det .hat{opacity:1;transform:none}
.mk .mg{opacity:0;transform:translate(40px,30px) scale(.6);transform-origin:122px 102px;transition:transform .5s cubic-bezier(.3,1.4,.5,1),opacity .3s}
.mk.det .mg{opacity:1;transform:none;animation:mkScan 2.6s .6s ease-in-out infinite}
@keyframes mkScan{0%,100%{transform:translate(-26px,2px) rotate(-6deg)}50%{transform:translate(6px,-2px) rotate(6deg)}}
.mk.det #eL{animation:none;transform:scaleY(.42)}
.mk.det #eR{animation:none;transform:scale(1.22)}
.mk.det .bl{transform:translateY(7px) rotate(10deg);transform-box:fill-box;transform-origin:center}
.mk.det .br{transform:translateY(-3px) rotate(-12deg);transform-box:fill-box;transform-origin:center}
.mk.det .pup{--ex:-3;--ey:1;animation:mkLook 2.6s .6s ease-in-out infinite}
@keyframes mkLook{0%,100%{transform:translate(-5px,1px)}50%{transform:translate(4px,0)}}
.lg-form p.tag.det{display:inline-block;position:relative;padding:9px 14px;border-radius:14px;background:#f0fdfc;color:#28958e;font-weight:600;font-size:14px;line-height:1.35;animation:lgUp .5s both}
.lg-form p.tag.det:after{content:"";position:absolute;right:-7px;top:16px;width:14px;height:14px;background:#f0fdfc;transform:rotate(45deg);border-radius:3px}
@media(max-width:520px){.mk{width:104px;right:-2px;top:-10px}.lg-form h1,.lg-form p.tag{max-width:calc(100% - 100px)}}
.mk .hap,.mk .mo4,.mk .tng{display:none}
.mk .hap{fill:none;stroke:#2a1a12;stroke-width:5;stroke-linecap:round}
.mk.win .eye,.mk.win .mo1,.mk.win .mo2,.mk.win .mo3,.mk.win .hat,.mk.win .mg{display:none}
.mk.win .hap,.mk.win .mo4,.mk.win .tng{display:block}
.mk.win .body{animation:mkJump .42s cubic-bezier(.3,0,.4,1) infinite alternate}
@keyframes mkJump{from{transform:translateY(0) rotate(-3deg)}to{transform:translateY(-14px) rotate(3deg)}}
.mk.win .hand{opacity:1;animation:mkCheer .42s ease-in-out infinite alternate}
.mk.win .hl{transform:translate(-52px,-58px) rotate(-18deg)}.mk.win .hr{transform:translate(52px,-58px) rotate(18deg)}
@keyframes mkCheer{from{margin-top:0}to{margin-top:-6px}}
.mk.win .brow{transform:translateY(-8px)}
.lg-form p.tag.win{display:inline-block;position:relative;padding:9px 14px;border-radius:14px;background:#e7f9ef;color:#0b7a46;font-weight:700;font-size:15px;animation:lgUp .4s both}
.lg-form p.tag.win:after{content:"";position:absolute;right:-7px;top:16px;width:14px;height:14px;background:#e7f9ef;transform:rotate(45deg);border-radius:3px}
.lg-cf{position:absolute;left:0;top:0;width:0;height:0;z-index:5;pointer-events:none}
.lg-cf i{position:absolute;width:9px;height:14px;border-radius:2px;opacity:0;animation:cfFly 1.5s cubic-bezier(.15,.7,.3,1) forwards}
@keyframes cfFly{0%{opacity:1;transform:translate(0,0) rotate(0) scale(.6)}15%{opacity:1}100%{opacity:0;transform:translate(var(--dx),var(--dy)) rotate(var(--r)) scale(1)}}
.lg-in{position:relative;margin-top:14px;animation:lgUp .7s both}
.lg-in.u{animation-delay:.45s}.lg-in.p{animation-delay:.55s}
.lg-in svg.ic{position:absolute;left:15px;top:50%;width:19px;height:19px;margin-top:-9.5px;fill:none;stroke:#81a3a1;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;transition:stroke .2s,transform .3s;pointer-events:none}
.lg-in input{width:100%;box-sizing:border-box;padding:16px 46px 16px 46px;font-size:15.5px;color:#121d1c;background:#f7fcfc;border:1.5px solid #e8f4f3;border-radius:14px;outline:0;transition:border-color .2s,box-shadow .2s,background .2s}
.lg-in input::placeholder{color:#81a3a1}
.lg-in input:hover{border-color:#d1ecea}
.lg-in input:focus{background:#fff;border-color:#1da79d;box-shadow:0 0 0 4px rgba(44,218,206,.16)}
.lg-in:focus-within svg.ic{stroke:#17837b;transform:scale(1.12)}
.lg-in input:-webkit-autofill{-webkit-text-fill-color:#121d1c;box-shadow:0 0 0 40px #f7fcfc inset}
.lg-eye{position:absolute;right:8px;top:50%;width:36px;height:36px;margin-top:-18px;padding:0;display:grid;place-items:center;background:transparent;border:0;border-radius:10px;cursor:pointer;color:#627e7c}
.lg-eye:hover{background:#eef9f9;color:#17837b}
.lg-eye svg{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.lg-eye .off{display:none}.lg-eye.on .off{display:block}.lg-eye.on .see{display:none}
.lg-form button.go{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;gap:10px;width:100%;margin-top:24px;padding:15px 20px;font-size:16px;font-weight:700;letter-spacing:.2px;color:#fff;background:linear-gradient(100deg,#17837b,#1b9f97,#17837b);background-size:200% 100%;border:0;border-radius:14px;cursor:pointer;box-shadow:0 12px 28px rgba(33,187,176,.38);transition:transform .15s,box-shadow .2s;animation:lgUp .7s .65s both,lgGrad 6s linear infinite}
@keyframes lgGrad{to{background-position:200% 0}}
.lg-form button.go::after{content:"";position:absolute;top:0;left:-60%;width:40%;height:100%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.5),transparent);transform:skewX(-20deg);animation:lgShine 3.6s 1.5s ease-in-out infinite}
@keyframes lgShine{0%,55%{left:-60%}100%{left:130%}}
.lg-form button.go svg{width:18px;height:18px;fill:none;stroke:#fff;stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round;transition:transform .25s}
.lg-form button.go:hover{transform:translateY(-2px);box-shadow:0 18px 36px rgba(33,187,176,.46)}
.lg-form button.go:hover svg{transform:translateX(5px)}
.lg-form button.go:active{transform:translateY(0) scale(.99)}
.lg-form button.go.busy{pointer-events:none;opacity:.9}
.lg-form button.go.busy svg{display:none}
.lg-spin{display:inline-block;width:17px;height:17px;border:2.5px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:lgSp .7s linear infinite}
@keyframes lgSp{to{transform:rotate(360deg)}}
.err{margin-top:16px;padding:11px 14px;border-radius:12px;color:#b42318;background:#fef3f2;border:1px solid #fecdca;font-size:14px;animation:lgUp .4s both}
.lg-foot{font-size:13px;color:#729491;animation:lgUp .7s .8s both}
.lg-foot b{color:#17837b;font-weight:700}
@keyframes lgUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
/* right scene */
.lg-right{position:relative;margin:14px 14px 14px 0;border-radius:22px;overflow:hidden;background:linear-gradient(135deg,#2dcac0 0%,#23bab0 48%,#eb9859 100%);background-size:160% 160%;animation:lgGr 14s ease-in-out infinite alternate;isolation:isolate;--px:0;--py:0}
@keyframes lgGr{to{background-position:100% 100%}}
.ly{position:absolute;transform:translate(calc(var(--px)*var(--d,10)*1px),calc(var(--py)*var(--d,10)*1px));transition:transform .25s ease-out}
.fl{animation:lgBob var(--t,6s) ease-in-out infinite;animation-delay:var(--dl,0s)}
@keyframes lgBob{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}
.cloud{position:absolute;height:auto;aspect-ratio:200/80;overflow:visible;fill:#fff;opacity:.95}
.cl1{width:46%;left:-6%;top:5%;animation:lgDrift 26s linear infinite alternate}
.cl2{width:40%;right:-8%;top:13%;opacity:.8;animation:lgDrift 32s linear infinite alternate-reverse}
.cl3{width:34%;right:-9%;bottom:-7%;opacity:.5;animation:lgDrift 30s linear infinite alternate}
.cl4{width:34%;left:-8%;bottom:14%;opacity:.7;animation:lgDrift 22s linear infinite alternate-reverse}
@keyframes lgDrift{from{transform:translateX(-18px)}to{transform:translateX(26px)}}
.lg-dash{left:15%;top:25%;width:68%;--d:16}
.dash{background:#fff;border-radius:20px;padding:16px 18px 14px;box-shadow:0 30px 60px rgba(21,70,67,.35);color:#121d1c}
.dash-h{display:flex;align-items:center;gap:6px;margin-bottom:12px}
.dash-h i{width:9px;height:9px;border-radius:50%;background:#ffd1d1}.dash-h i:nth-child(2){background:#ffe9b0}.dash-h i:nth-child(3){background:#c9f2d3}
.dash-h span{margin-left:auto;font-size:11.5px;font-weight:700;color:#627e7c}
.dash-top{display:flex;align-items:center;gap:14px}
.donut{position:relative;width:74px;height:74px;flex:none}
.donut svg{width:74px;height:74px;transform:rotate(-90deg)}
.donut circle{fill:none;stroke-width:9}
.donut .bg{stroke:#effaf9}
.donut .fg{stroke:url(#lgdg);stroke-linecap:round;stroke-dasharray:0 214;animation:lgDonut 2s 1s cubic-bezier(.3,.8,.3,1) forwards}
@keyframes lgDonut{to{stroke-dasharray:154 214}}
.donut b{position:absolute;inset:0;display:grid;place-items:center;font-size:17px;font-weight:800;letter-spacing:-.5px}
.dash-k{flex:1;min-width:0}
.dash-k div{display:flex;align-items:center;gap:7px;font-size:11.5px;color:#576a69;margin-bottom:7px}
.dash-k div i{width:8px;height:8px;border-radius:50%;background:#1da79d}.dash-k div:nth-child(2) i{background:#f59e0b}
.dash-k div s{flex:1;height:6px;border-radius:4px;background:#effaf9;text-decoration:none;position:relative;overflow:hidden}
.dash-k div s::after{content:"";position:absolute;inset:0;width:0;border-radius:4px;background:#1da79d;animation:lgFill 1.6s 1.2s cubic-bezier(.3,.8,.3,1) forwards}
.dash-k div:nth-child(2) s::after{background:#f59e0b;--w:28%}
.dash-k div s::after{--w:72%}
@keyframes lgFill{to{width:var(--w)}}
.bars{position:relative;display:flex;align-items:flex-end;gap:9px;height:96px;margin-top:16px;padding-top:4px;border-top:1px dashed #eaf4f4}
.bars i{flex:1;border-radius:7px 7px 3px 3px;background:linear-gradient(180deg,#9fefea,#1da79d);height:0;animation:lgGrow .9s cubic-bezier(.3,1.3,.4,1) forwards,lgBreathe 3.6s ease-in-out infinite;animation-delay:calc(1s + var(--i)*.09s),calc(2.4s + var(--i)*.25s)}
.bars i:nth-child(4){background:linear-gradient(180deg,#f9d2b4,#c86e29)}
@keyframes lgGrow{to{height:var(--h)}}
@keyframes lgBreathe{0%,100%{transform:scaleY(1)}50%{transform:scaleY(.86)}}
.bars i{transform-origin:bottom}
.bars svg{position:absolute;left:0;right:0;bottom:0;width:100%;height:100%;overflow:visible;pointer-events:none}
.bars path{fill:none;stroke:#121d1c;stroke-width:2.2;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:420;stroke-dashoffset:420;animation:lgDraw 2.2s 1.8s ease forwards}
@keyframes lgDraw{to{stroke-dashoffset:0}}
.bars circle{fill:#fff;stroke:#121d1c;stroke-width:2.2;opacity:0;animation:lgPop .4s 3.9s both}
@keyframes lgPop{from{opacity:0;transform:scale(0)}to{opacity:1;transform:none}}
.chip{display:flex;align-items:center;gap:9px;padding:9px 14px 9px 10px;background:#fff;border-radius:14px;box-shadow:0 14px 30px rgba(21,70,67,.28);font-size:12.5px;font-weight:700;color:#121d1c;white-space:nowrap}
.chip em{display:grid;place-items:center;width:26px;height:26px;border-radius:9px;font-style:normal;font-size:14px;background:#eafcfb}
.chip small{display:block;font-size:10.5px;font-weight:600;color:#627e7c}
.lg-c1{left:3%;top:62%;--d:26}.lg-c1 .chip{animation:lgChip 7s 2s ease-in-out infinite both}
.lg-c2{right:3%;top:17%;--d:22}.lg-c2 .chip{animation:lgChip 7s 4.4s ease-in-out infinite both}
.lg-c2 em{background:#dcfce7}
@keyframes lgChip{0%{opacity:0;transform:translateY(14px) scale(.9)}8%,70%{opacity:1;transform:none}80%,100%{opacity:0;transform:translateY(-10px) scale(.95)}}
.lg-lock{right:6%;bottom:17%;width:21%;--d:30}
.lg-lock .sh{transform-origin:78% 100%;animation:lgShackle 6s 2.5s ease-in-out infinite}
@keyframes lgShackle{0%,12%,100%{transform:translateY(0) rotate(0)}18%,40%{transform:translateY(-7px) rotate(-14deg)}48%{transform:translateY(0) rotate(0)}}
.lg-lock .kh{animation:lgGlow 6s 2.5s ease-in-out infinite}
@keyframes lgGlow{0%,12%,100%{fill:#b7f4f0}18%,40%{fill:#34d399}}
.lg-cap{left:8%;top:12%;width:17%;--d:34}
.lg-cap .tas{transform-origin:86% 40%;animation:lgSwing 3s ease-in-out infinite alternate}
@keyframes lgSwing{from{transform:rotate(-10deg)}to{transform:rotate(10deg)}}
.spk{position:absolute;width:12px;height:12px;background:#fff;clip-path:polygon(50% 0,62% 38%,100% 50%,62% 62%,50% 100%,38% 62%,0 50%,38% 38%);opacity:0;animation:lgTw 3.4s ease-in-out infinite}
@keyframes lgTw{0%,100%{opacity:0;transform:scale(.3) rotate(0)}50%{opacity:.95;transform:scale(1.2) rotate(90deg)}}
.lg-copy{position:absolute;left:0;right:0;bottom:0;padding:70px 34px 26px;background:linear-gradient(180deg,transparent,rgba(38,109,104,.5));color:#fff;z-index:3;text-align:center;animation:lgUp .8s 1.2s both}
.lg-copy h2{margin:0 0 5px;font-size:21px;letter-spacing:-.4px;font-weight:800;text-shadow:0 2px 14px rgba(23,89,84,.35)}
.lg-copy p{margin:0;font-size:13.5px;opacity:.92;text-shadow:0 1px 10px rgba(23,89,84,.3)}
.lg-dots{display:flex;gap:6px;justify-content:center;margin-top:12px}
.lg-dots i{width:7px;height:7px;border-radius:50%;background:rgba(255,255,255,.5);animation:lgDot 3s ease-in-out infinite}
.lg-dots i:nth-child(2){animation-delay:1s}.lg-dots i:nth-child(3){animation-delay:2s}
@keyframes lgDot{0%,100%{width:7px;background:rgba(255,255,255,.5)}33%{width:22px;background:#fff;border-radius:4px}}
@media(max-width:900px){.lg-card{grid-template-columns:1fr;height:auto;min-height:0}.lg-right{display:none}.lg-left{padding:32px 26px 24px;gap:28px}.lg-form h1{font-size:34px}body.lg{overflow:auto;align-items:start}}
@media(max-height:640px){.lg-card{height:auto}}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important}.bars i{height:var(--h)!important}.donut .fg{stroke-dasharray:154 214!important}.bars path{stroke-dashoffset:0!important}.bars circle{opacity:1!important}.dash-k div s::after{width:var(--w)!important}}
</style>
<script>document.body.classList.add('lg')</script>
<div class="lg-bgb a"></div><div class="lg-bgb b"></div>
<div class="lg-card<?= $error !== '' ? ' shake' : '' ?>">
 <div class="lg-left">
  <div class="lg-brand"><div class="lg-mark" aria-hidden="true"><i></i><i></i><i></i><i></i></div>Pranav Pipeline</div>
  <form class="lg-form" method="post" autocomplete="on" id="lgform">
   <input type="hidden" name="login" value="1">
   <svg class="mk" id="mk" viewBox="0 0 200 190" aria-hidden="true">
    <g class="body">
     <circle cx="36" cy="106" r="22" fill="#8a5632"/><circle cx="36" cy="106" r="12" fill="#f0c7a0"/>
     <circle cx="164" cy="106" r="22" fill="#8a5632"/><circle cx="164" cy="106" r="12" fill="#f0c7a0"/>
     <circle cx="100" cy="102" r="64" fill="#9a6338"/>
     <path d="M100 72c-14-14-46-6-46 24 0 14 6 22 14 28 4 18 18 30 32 30s28-12 32-30c8-6 14-14 14-28 0-30-32-38-46-24z" fill="#f4d5b0"/>
     <g class="eye" id="eL"><ellipse cx="78" cy="102" rx="13" ry="15" fill="#fff"/><g class="pup"><circle cx="78" cy="104" r="7" fill="#2a1a12"/><circle cx="80.5" cy="101" r="2.3" fill="#fff"/></g></g>
     <g class="eye" id="eR"><ellipse cx="122" cy="102" rx="13" ry="15" fill="#fff"/><g class="pup"><circle cx="122" cy="104" r="7" fill="#2a1a12"/><circle cx="124.5" cy="101" r="2.3" fill="#fff"/></g></g>
     <path class="hap" d="M66 106q12-16 24 0"/><path class="hap" d="M110 106q12-16 24 0"/><path class="brow bl" d="M64 82q14-9 28-1"/><path class="brow br" d="M108 81q14-8 28 1"/>
     <ellipse cx="100" cy="127" rx="9" ry="6" fill="#6b3f22"/>
     <path class="mo mo1" d="M84 141q16 14 32 0"/><path class="mo mo2" d="M86 140q18 12 30 -4"/><path class="mo mo3" d="M86 142q14 -5 30 1"/><path class="mo4" d="M80 136q20 30 40 0z" fill="#4a1f12" stroke="#4a1f12" stroke-width="3" stroke-linejoin="round"/><path class="tng" d="M92 152q8 8 16 0q-8-6 -16 0z" fill="#ef6f85"/>
     <g class="hat"><ellipse cx="100" cy="50" rx="70" ry="13" fill="#253231"/><path d="M62 50c0-30 10-42 38-42s38 12 38 42z" fill="#354342"/><rect x="62" y="38" width="76" height="9" fill="#c86e29"/></g>
    </g>
    <g class="mg"><path d="M134 116 166 150" stroke="#6b3f22" stroke-width="8" stroke-linecap="round"/><circle cx="170" cy="154" r="9" fill="#9a6338"/><circle cx="122" cy="102" r="22" fill="rgba(255,255,255,.28)" stroke="#e0a800" stroke-width="5"/><path d="M110 94a14 14 0 0 1 10-8" stroke="#fff" stroke-width="3" stroke-linecap="round" fill="none" opacity=".8"/></g>
    <g class="hand hl"><ellipse cx="80" cy="102" rx="25" ry="22" fill="#9a6338"/><ellipse cx="80" cy="106" rx="16" ry="12" fill="#f0c7a0" opacity=".55"/><circle cx="62" cy="86" r="7" fill="#9a6338"/><circle cx="73" cy="80" r="7" fill="#9a6338"/><circle cx="85" cy="79" r="7" fill="#9a6338"/><circle cx="97" cy="83" r="7" fill="#9a6338"/></g>
    <g class="hand hr"><ellipse cx="120" cy="102" rx="25" ry="22" fill="#9a6338"/><ellipse cx="120" cy="106" rx="16" ry="12" fill="#f0c7a0" opacity=".55"/><circle cx="103" cy="83" r="7" fill="#9a6338"/><circle cx="115" cy="79" r="7" fill="#9a6338"/><circle cx="127" cy="80" r="7" fill="#9a6338"/><circle cx="138" cy="86" r="7" fill="#9a6338"/></g>
   </svg>
   <div class="mk-say" id="mksay" role="status">Hi! I’m <b>Pranav</b>, the bot built for this dashboard 👋</div>
   <h1>Hello,<br>Welcome Back <span class="lg-wave">👋</span></h1>
   <p class="tag<?= $error !== '' ? ' det' : '' ?>" id="lgtag" data-o="Log in to see your admissions data, live."><?= $error !== '' ? 'Hmm… not the right one. Try again, let me see if it is you 🔍' : 'Log in to see your admissions data, live.' ?></p>
   <div class="lg-in u"><svg class="ic" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg><input id="u" name="username" autocomplete="username" placeholder="Username" aria-label="Username" required autofocus></div>
   <div class="lg-in p"><svg class="ic" viewBox="0 0 24 24"><rect x="4" y="10.5" width="16" height="10" rx="2.5"/><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5"/></svg><input id="p" name="password" type="password" autocomplete="current-password" placeholder="Password" aria-label="Password" required><button type="button" class="lg-eye" id="lgeye" aria-label="Show password" title="Show password"><svg class="see" viewBox="0 0 24 24"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg><svg class="off" viewBox="0 0 24 24"><path d="M3 3l18 18M10.6 5.1A9.7 9.7 0 0 1 12 5c6.4 0 10 7 10 7a17 17 0 0 1-3.2 4.1M6.5 6.6A16.6 16.6 0 0 0 2 12s3.6 7 10 7a9.6 9.6 0 0 0 4.4-1M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg></button></div>
   <button class="go" id="lggo" type="submit"><b>Log in</b><svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
   <?php if ($error !== '') { echo '<div class="err">' . h($error) . '</div>'; } ?>
  </form>
  <div class="lg-foot">Need access? Ask your <b>admin</b> for a login.</div>
 </div>
 <div class="lg-right" id="lgscene" aria-hidden="true">
  <svg width="0" height="0" style="position:absolute"><defs><linearGradient id="lgdg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2cdace"/><stop offset="1" stop-color="#ea8b42"/></linearGradient>
   <symbol id="lgcl" viewBox="0 0 200 80"><path d="M20 80a20 20 0 0 1-2-39.9A32 32 0 0 1 78 28a38 38 0 0 1 72 6 26 26 0 0 1 32 26 20 20 0 0 1-8 20z"/></symbol></defs></svg>
  <svg class="cloud cl1" viewBox="0 0 200 80"><use href="#lgcl"/></svg><svg class="cloud cl2" viewBox="0 0 200 80"><use href="#lgcl"/></svg><svg class="cloud cl3" viewBox="0 0 200 80"><use href="#lgcl"/></svg><svg class="cloud cl4" viewBox="0 0 200 80"><use href="#lgcl"/></svg>
  <span class="spk" style="left:12%;top:36%;animation-delay:.3s"></span><span class="spk" style="left:84%;top:46%;animation-delay:1.4s;width:9px;height:9px"></span><span class="spk" style="left:70%;top:9%;animation-delay:2.2s"></span><span class="spk" style="left:24%;top:78%;animation-delay:.9s;width:9px;height:9px"></span><span class="spk" style="left:92%;top:78%;animation-delay:1.9s;width:10px;height:10px"></span>
  <div class="ly lg-cap"><div class="fl" style="--t:5s;--dl:-1s"><svg viewBox="0 0 120 100"><path d="M60 8 112 34 60 60 8 34z" fill="#173230"/><path d="M28 50v22c0 8 15 16 32 16s32-8 32-16V50L60 66z" fill="#234c49"/><g class="tas"><path d="M104 38v32" stroke="#fbbf24" stroke-width="3.5" stroke-linecap="round"/><circle cx="104" cy="74" r="5" fill="#fbbf24"/></g></svg></div></div>
  <div class="ly lg-dash"><div class="fl" style="--t:7s"><div class="dash">
   <div class="dash-h"><i></i><i></i><i></i><span>Re-registration</span></div>
   <div class="dash-top"><div class="donut"><svg viewBox="0 0 80 80"><circle class="bg" cx="40" cy="40" r="34"/><circle class="fg" cx="40" cy="40" r="34"/></svg><b id="lgpct">0%</b></div>
    <div class="dash-k"><div><i></i>Done<s></s></div><div><i></i>Pending<s></s></div></div></div>
   <div class="bars"><i style="--h:38%;--i:0"></i><i style="--h:58%;--i:1"></i><i style="--h:46%;--i:2"></i><i style="--h:84%;--i:3"></i><i style="--h:64%;--i:4"></i><i style="--h:92%;--i:5"></i><i style="--h:72%;--i:6"></i>
    <svg viewBox="0 0 200 90" preserveAspectRatio="none"><path d="M6 66 38 50 66 58 96 24 126 40 158 14 192 28" vector-effect="non-scaling-stroke"/></svg></div>
  </div></div></div>
  <div class="ly lg-c1"><div class="fl" style="--t:5.5s"><div class="chip"><em>💰</em><span>Payment received<small>just now</small></span></div></div></div>
  <div class="ly lg-c2"><div class="fl" style="--t:6.5s;--dl:-2s"><div class="chip"><em>🎓</em><span>+12 re-registered<small>this week</small></span></div></div></div>
  <div class="ly lg-lock"><div class="fl" style="--t:6s;--dl:-3s"><svg viewBox="0 0 100 120"><g class="sh"><path d="M26 54V38a24 24 0 0 1 48 0v16" fill="none" stroke="#fff" stroke-width="12" stroke-linecap="round"/></g><rect x="8" y="50" width="84" height="64" rx="16" fill="#fff"/><circle class="kh" cx="50" cy="78" r="8" fill="#b7f4f0"/><rect class="kh" x="46.5" y="80" width="7" height="16" rx="3.5" fill="#b7f4f0"/></svg></div></div>
  <div class="lg-copy"><h2>Every university. One live view.</h2><p>Admissions, payments and re-registrations, always up to date.</p><div class="lg-dots"><i></i><i></i><i></i></div></div>
 </div>
</div>
<script>
(function () {
  var f = document.getElementById('lgform'), go = document.getElementById('lggo'), eye = document.getElementById('lgeye'), pw = document.getElementById('p');
  eye.onclick = function () { var show = pw.type === 'password'; pw.type = show ? 'text' : 'password'; eye.classList.toggle('on', show); eye.title = show ? 'Hide password' : 'Show password'; eye.setAttribute('aria-label', eye.title); };
  var mk = document.getElementById('mk'), us = document.getElementById('u'), tg = document.getElementById('lgtag');
  var det = <?= $error !== '' ? 'true' : 'false' ?>, foc = '';
  function look(x, y) { mk.style.setProperty('--ex', x.toFixed(1)); mk.style.setProperty('--ey', y.toFixed(1)); }
  function mood() {
    var c = foc === 'win' ? 'win' : det ? 'det' : foc === 'p' ? (pw.type === 'text' ? 'pk' : 'pw') : foc === 'u' ? 'u' : '';
    mk.setAttribute('class', 'mk' + (c ? ' ' + c : ''));
  }
  function clearDet() { if (!det) return; det = false; if (tg) { tg.className = 'tag'; tg.textContent = tg.getAttribute('data-o'); } mood(); }
  function track() { var n = us.value.length, c = us.selectionStart == null ? n : us.selectionStart; look(Math.max(-5, Math.min(5, -5 + c * 0.55)), 4); }
  us.addEventListener('focus', function () { foc = 'u'; track(); mood(); });
  us.addEventListener('input', function () { clearDet(); track(); });
  us.addEventListener('keyup', track); us.addEventListener('click', track);
  us.addEventListener('blur', function () { if (foc === 'u') foc = ''; mood(); });
  pw.addEventListener('focus', function () { foc = 'p'; clearDet(); mood(); });
  pw.addEventListener('input', clearDet);
  pw.addEventListener('blur', function () { if (foc === 'p') foc = ''; mood(); });
  eye.addEventListener('mousedown', function (e) { e.preventDefault(); if (foc !== 'p') pw.focus(); });
  eye.addEventListener('click', function () { mood(); });
  if (document.activeElement === us) { foc = 'u'; track(); }
  mood();
  document.addEventListener('mousemove', function (e) { if (foc === 'p' || det || foc === 'win') return; var r = mk.getBoundingClientRect(); var dx = (e.clientX - (r.left + r.width / 2)) / 260, dy = (e.clientY - (r.top + r.height / 2)) / 260; look(Math.max(-5, Math.min(5, dx * 5)), Math.max(-3, Math.min(5, dy * 5))); });
  var card = document.querySelector('.lg-card'), busy = false, goHtml = go.innerHTML;
  function confetti() {
    var box = document.createElement('div'); box.className = 'lg-cf';
    var r = mk.getBoundingClientRect(), c = card.getBoundingClientRect();
    box.style.left = (r.left - c.left + r.width / 2) + 'px'; box.style.top = (r.top - c.top + r.height * .45) + 'px';
    var cols = ['#2cdace', '#ec4899', '#fbbf24', '#22c55e', '#38bdf8', '#f97316'];
    for (var k = 0; k < 44; k++) {
      var p = document.createElement('i'), a = Math.random() * Math.PI * 2, d = 90 + Math.random() * 190;
      p.style.background = cols[k % cols.length]; p.style.setProperty('--dx', (Math.cos(a) * d).toFixed(0) + 'px'); p.style.setProperty('--dy', (Math.sin(a) * d - 60 + Math.random() * 120).toFixed(0) + 'px'); p.style.setProperty('--r', (Math.random() * 720 - 360).toFixed(0) + 'deg'); p.style.animationDelay = (Math.random() * .15).toFixed(2) + 's';
      box.appendChild(p);
    }
    card.appendChild(box); setTimeout(function () { box.remove(); }, 2000);
  }
  function win(url) {
    det = false; foc = 'win'; mk.setAttribute('class', 'mk win');
    if (tg) { tg.className = 'tag win'; tg.textContent = 'It’s you! Come on in'; }
    go.classList.remove('busy'); go.innerHTML = '<b>Welcome! ✓</b>';
    var er = f.querySelector('.err'); if (er) er.remove();
    if (!reduce0) { card.style.transition = 'opacity .5s ease, transform .5s ease'; document.body.style.transition = 'background .5s ease'; setTimeout(function () { card.style.opacity = '0'; card.style.transform = 'translateY(-14px) scale(.985)'; document.body.style.background = '#113f3c'; }, 180); }
    setTimeout(function () { location.href = url; }, reduce0 ? 300 : 760);
  }
  function lose(msg) {
    busy = false; go.classList.remove('busy'); go.innerHTML = goHtml; pw.value = '';
    var er = f.querySelector('.err'); if (!er) { er = document.createElement('div'); er.className = 'err'; f.appendChild(er); } er.textContent = msg;
    card.classList.remove('shake'); void card.offsetWidth; card.classList.add('shake');
    det = true; foc = ''; if (tg) { tg.className = 'tag det'; tg.textContent = 'Hmm… not the right one. Try again, let me see if it is you 🔍'; }
    mood(); us.focus(); us.select();
  }
  var reduce0 = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  f.addEventListener('submit', function (e) {
    e.preventDefault(); if (busy) return; busy = true;
    go.classList.add('busy'); go.innerHTML = '<span class="lg-spin"></span><b>Signing in…</b>';
    var body = new URLSearchParams(new FormData(f));
    fetch(f.getAttribute('action') || location.href, {method: 'POST', body: body, credentials: 'same-origin', redirect: 'manual'}).then(function (r) {
      if (r.type === 'opaqueredirect' || (r.status >= 300 && r.status < 400)) { win(location.pathname); return null; }
      return r.text();
    }).then(function (t) {
      if (t === null || t === undefined) return;
      var d = new DOMParser().parseFromString(t, 'text/html'), er = d.querySelector('.err');
      if (er) lose(er.textContent || 'Wrong username or password.');
      else if (!d.getElementById('lgform')) win(location.pathname);
      else lose('Could not log in. Please try again.');
    }).catch(function () { f.submit(); });
  });
  var sc = document.getElementById('lgscene'), pc = document.getElementById('lgpct');
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) { pc.textContent = '72%'; return; }
  var t0 = null;
  function cnt(ts) { if (!t0) t0 = ts; var k = Math.min(1, (ts - t0 - 1000) / 2000); if (k > 0) pc.textContent = Math.round(72 * (1 - Math.pow(1 - k, 3))) + '%'; if (k < 1) requestAnimationFrame(cnt); }
  requestAnimationFrame(cnt);
  document.addEventListener('mousemove', function (e) { var r = sc.getBoundingClientRect(); sc.style.setProperty('--px', ((e.clientX - r.left) / r.width - .5).toFixed(3)); sc.style.setProperty('--py', ((e.clientY - r.top) / r.height - .5).toFixed(3)); });
})();
</script>
</body></html>
<?php
    exit;
}

if (isset($_GET['imglist'])) {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    $imgs = array();
    $imgrel = '';
    foreach (array('images/universities', 'images/images/universities', 'universities') as $cand) {
        $imgdir = __DIR__ . '/' . $cand;
        if (!is_dir($imgdir)) { continue; }
        foreach (scandir($imgdir) as $imgf) {
            if (preg_match('/^[^.].*\.(jpe?g|png|webp|avif|gif)$/i', $imgf)) { $imgs[] = $imgf; }
        }
        if ($imgs) { $imgrel = $cand; break; }
    }
    echo json_encode(array('dir' => $imgrel, 'files' => $imgs));
    exit;
}

page_start('Pranav Pipeline');
?>
<style>
.wrap{max-width:1500px;margin:0 auto;padding:16px}
header{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;margin-bottom:12px}
h1{font-size:20px;margin:0}.sub{color:var(--muted);font-size:13px}
.actions{display:flex;gap:8px;align-items:center}
button{font:inherit;padding:8px 14px;border-radius:8px;border:1px solid var(--accent);background:var(--accent);color:var(--accent-t);cursor:pointer;font-weight:600}
button.ghost{background:transparent;color:var(--text);border-color:var(--line);font-weight:500}
button:disabled{opacity:.6;cursor:default}
.banner{background:var(--warn);color:var(--warn-t);padding:10px 14px;border-radius:8px;margin-bottom:12px;font-size:14px}
.chips{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px}
.chip{padding:5px 11px;border:1px solid var(--line);border-radius:999px;background:var(--card);color:var(--text);font-size:13px;font-weight:500;cursor:pointer}
.chip.on{background:var(--accent);color:var(--accent-t);border-color:var(--accent)}
.toolbar{display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:10px}
input[type=search]{flex:1 1 260px;min-width:0;padding:9px 12px;font:inherit;color:var(--text);background:var(--card);border:1px solid var(--line);border-radius:8px}
details.cols{position:relative}
details.cols summary{list-style:none;cursor:pointer;padding:8px 14px;border:1px solid var(--line);border-radius:8px;background:var(--card)}
details.cols summary::-webkit-details-marker{display:none}
.panel{position:absolute;z-index:5;top:42px;left:0;width:min(360px,88vw);max-height:60vh;overflow:auto;background:var(--card);border:1px solid var(--line);border-radius:10px;padding:10px;box-shadow:0 8px 24px rgba(0,0,0,.15)}
.panel .row{display:flex;gap:8px;margin-bottom:8px}
.panel label{display:flex;gap:8px;align-items:flex-start;padding:4px 2px;font-size:14px}
.tablewrap{overflow:auto;background:var(--card);border:1px solid var(--line);border-radius:10px;max-height:72vh}
table{border-collapse:collapse;width:100%;font-size:13.5px}
th,td{padding:8px 12px;border-bottom:1px solid var(--line);text-align:left;white-space:nowrap;max-width:280px;overflow:hidden;text-overflow:ellipsis}
th{position:sticky;top:0;background:var(--card);cursor:pointer;user-select:none;z-index:1;font-weight:600}
tbody tr:hover td{background:rgba(127,127,127,.08)}
td.num{text-align:right;font-variant-numeric:tabular-nums}
.pager{display:flex;gap:12px;align-items:center;justify-content:center;margin-top:12px}
.empty{padding:40px;text-align:center;color:var(--muted)}
.unihead{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;margin-bottom:12px}
.unihead h2{font-size:16px;margin:0}
.matrixwrap{margin-bottom:14px}
.matrixwrap .cap{display:flex;flex-wrap:wrap;gap:8px;align-items:baseline;justify-content:space-between;margin-bottom:6px}
.matrixwrap .cap b{font-size:14px}
.mxscroll{overflow:auto;background:var(--card);border:1px solid var(--line);border-radius:10px}
table.mx{width:auto;min-width:100%}
table.mx th,table.mx td{text-align:center;cursor:default;position:static;max-width:none;padding:4px 6px}
table.mx th.l,table.mx td.l{text-align:left}
table.mx .tot{color:var(--muted);font-weight:600;padding:6px 10px}
table.mx .zero{color:var(--muted)}
button.mxb{width:100%;min-width:56px;padding:6px 10px;background:transparent;color:var(--text);border:1px solid transparent;border-radius:6px;font-weight:500;font-variant-numeric:tabular-nums;cursor:pointer}
button.mxb:hover{border-color:var(--accent)}
button.mxb.on{background:var(--accent);color:var(--accent-t);border-color:var(--accent)}
button.mxb.h{font-weight:600}
button.mxb.rh{text-align:left;white-space:nowrap}
.ovhead{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;margin-bottom:14px}
.ovhead .dashtitle{margin:0;font-size:22px}
a.btnlink{padding:9px 16px;border-radius:8px;background:var(--accent);color:var(--accent-t);text-decoration:none;font-weight:600;font-size:14px}
.kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px;margin-bottom:14px}
.kpi{padding:14px 16px;background:var(--card);border:1px solid var(--line);border-left:4px solid var(--accent);border-radius:10px}
.kpi span{display:block;color:var(--muted);font-size:13px}
.kpi b{font-size:26px;font-variant-numeric:tabular-nums}
.charts{--s1:#2a78d6;--s2:#eb6834;--s3:#1baf7a;--s4:#eda100;--s5:#e87ba4;--s6:#008300;--s7:#316d69;--other:#98a2b3;display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,460px),1fr));gap:14px;margin-bottom:14px}
@media (prefers-color-scheme:dark){.charts{--s1:#3987e5;--s2:#d95926;--s3:#199e70;--s4:#c98500;--s5:#d55181;--s6:#008300;--s7:#63d1c9;--other:#667085}}
.chartcard{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:16px 18px;min-width:0}
.chartcard.wide{grid-column:1/-1}
.chartcard h3{margin:0;font-size:15px}
.chartcard .sub{margin:2px 0 12px}
.lgd{display:flex;flex-wrap:wrap;gap:6px 16px;margin:0 0 8px;font-size:13px}
.lg{display:inline-flex;gap:6px;align-items:center}
.lg i{width:11px;height:11px;border-radius:3px;display:inline-block}
svg.stk{width:100%;max-width:820px;height:auto;display:block}
.stk .grid{stroke:var(--line);stroke-width:1}
.stk .seg{stroke:var(--card);stroke-width:1.5}
.stk a:hover .seg,.stk .seg:hover{opacity:.82}
.stk .ax{font-size:11px;fill:var(--muted)}
.stk .at{font-size:12px;font-weight:600;fill:var(--text)}
.stk .tv{font-size:11px;font-weight:600;fill:var(--text)}
.stk .ln1{fill:none;stroke:var(--s2);stroke-width:2.5;stroke-linejoin:round}
.stk .area{fill:var(--s2);opacity:.14}
.stk .pt{fill:var(--s2);stroke:var(--card);stroke-width:2}
.stk .pt:hover{r:7}
#dash,#overview{--s1:#2a78d6;--s2:#eb6834;--s3:#1baf7a;--s4:#eda100;--s5:#e87ba4;--s6:#008300;--s7:#316d69;--other:#98a2b3}
@media (prefers-color-scheme:dark){#dash,#overview{--s1:#3987e5;--s2:#d95926;--s3:#199e70;--s4:#c98500;--s5:#d55181;--s6:#008300;--s7:#63d1c9;--other:#667085}}
.sheethead{background:linear-gradient(120deg,#3b5bdb,#2baea6 60%,#c2255c);color:#fff;border-radius:12px;padding:14px 20px 16px;margin-bottom:14px;box-shadow:0 6px 18px rgba(59,91,219,.25)}
.sheethead .back{color:rgba(255,255,255,.88);margin-bottom:4px}
.sheethead .dashtitle{margin:0;font-size:22px;color:#fff}
.sheethead.ov{display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between}
.sheethead .btnlink{background:#fff;color:#285d59;box-shadow:0 2px 8px rgba(0,0,0,.15)}
.kpi{background:color-mix(in srgb,var(--c,var(--accent)) 9%,var(--card));border-left-color:var(--c,var(--accent))}
.mxscroll{display:block;width:min(100%,1400px);border-radius:12px;box-shadow:0 4px 16px rgba(20,30,60,.08);border-color:transparent}
table.mx{border-collapse:separate;border-spacing:0}
table.mx thead th{background:#285d59;color:#fff;border-bottom:0;padding:9px 10px}
table.mx thead button.mxb{color:#fff}
table.mx thead button.mxb:hover{border-color:rgba(255,255,255,.6)}
table.mx thead th.tot{color:rgba(255,255,255,.9)}
.mxscroll{overflow-x:auto}
table.mx tbody tr:nth-child(even){background:rgba(59,91,219,.05)}
table.mx tbody tr:hover{background:rgba(43,174,166,.11)}
table.mx tbody tr:hover td{background:transparent}
table.mx tbody tr:last-child{background:rgba(43,174,166,.09)}
table.mx tbody tr:last-child td{border-top:2px solid var(--line);font-weight:700}
button.mxb.heat{display:block;width:100%;padding:7px 4px;border-radius:8px;border:0;font-weight:700;color:var(--text);background:rgba(59,91,219,var(--i))}
button.mxb.heat.dk{color:#fff}
button.mxb.heat:hover{outline:2px solid #2baea6;outline-offset:-2px}
table.mx{table-layout:fixed;width:100%}
table.mx th,table.mx td{padding:3px 5px;overflow:hidden}
table.mx thead th{padding:12px 8px;text-align:center}
table.mx thead th.l{text-align:left;padding-left:16px}
table.mx td.zero{text-align:center}
table.mx td.zero span{color:var(--muted);opacity:.55}
table.mx td.l{padding-left:14px}
table.mx td.tot.ctr{text-align:center}
table.mx td.tot:not(.ctr),table.mx th.tot{text-align:left;padding-left:22px}
button.mxb.rh{display:block;width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.tb{display:flex;align-items:center;gap:10px}
.tb span{min-width:34px;text-align:right;color:var(--text);font-weight:600}
.tb i{display:block;height:8px;border-radius:4px;background:linear-gradient(90deg,#3b5bdb,#2baea6)}
tbody tr:last-child .tb span{font-weight:700}
.cd{display:inline-block;width:10px;height:10px;border-radius:3px;margin-right:9px;vertical-align:middle}
@media (prefers-color-scheme:dark){table.mx thead th{background:#1f4643}}
#dash .chip{border-color:transparent;box-shadow:0 1px 2px rgba(0,0,0,.08)}
#dash .chip.on{background:linear-gradient(120deg,#3b5bdb,#2baea6);color:#fff}
#dash .tablewrap{border-radius:12px;box-shadow:0 4px 16px rgba(20,30,60,.08);border-color:transparent}
#dash table{border-collapse:separate;border-spacing:0}
#dash th{background:#285d59;color:#fff;border-bottom:0;letter-spacing:.2px;font-size:13px;padding:11px 14px}
#dash th:hover{background:#316d69}
#dash td{padding:9px 14px;border-bottom:1px solid var(--line)}
#dash tbody tr:nth-child(even) td{background:rgba(59,91,219,.05)}
#dash tbody tr:hover td{background:rgba(43,174,166,.13)}
#dash .cm{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;white-space:normal;min-width:260px;max-width:420px;line-height:1.35}
#dash td:has(.cm){white-space:normal;max-width:440px}
#dash .idc{color:var(--muted);font-variant-numeric:tabular-nums}
#dash .nm{font-weight:600}
#dash .dash{color:var(--muted);opacity:.6}
#dash td.num{font-weight:600}
.pill{display:inline-flex;align-items:center;gap:6px;padding:2px 10px 2px 8px;border-radius:999px;font-size:12.5px;font-weight:600;line-height:1.6;background:color-mix(in srgb,var(--c) 16%,transparent);border:1px solid color-mix(in srgb,var(--c) 40%,transparent)}
.pill i{width:8px;height:8px;border-radius:50%;background:var(--c);flex:none}
@media (prefers-color-scheme:dark){#dash th{background:#1f4643}#dash th:hover{background:#285754}}
#dash th.editcol,#dash td.editcol{position:sticky;right:0;z-index:2;text-align:center;padding:6px 10px;box-shadow:-6px 0 8px -6px rgba(0,0,0,.18)}
#dash th.editcol{background:#285d59;cursor:default;z-index:3}
#dash td.editcol{background:var(--card)}
#dash tbody tr:nth-child(even) td.editcol{background:color-mix(in srgb,#3b5bdb 5%,var(--card))}
#dash tbody tr:hover td.editcol{background:color-mix(in srgb,#2baea6 13%,var(--card))}
button.sm{padding:5px 12px;font-size:13px;border-radius:8px}
.modal{position:fixed;inset:0;z-index:50;background:rgba(15,20,40,.55);display:flex;align-items:center;justify-content:center;padding:16px}
.modal[hidden]{display:none}
.mbox{background:var(--card);color:var(--text);width:min(960px,100%);max-height:94vh;border-radius:16px;display:flex;flex-direction:column;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.35)}
.mhead{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;padding:16px 22px;background:linear-gradient(120deg,#3b5bdb,#2baea6 60%,#c2255c);color:#fff}
.sfoot .who{display:flex;align-items:center;gap:10px;padding:8px 10px;margin-bottom:8px;border-radius:10px;background:rgba(255,255,255,.05);color:#e7ecfb}
.sfoot .who .av{flex:none;width:30px;height:30px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;background:linear-gradient(135deg,#3b5bdb,#c2255c);color:#fff}
.sfoot .who .t{display:flex;flex-direction:column;min-width:0;line-height:1.2}.sfoot .who small{font-size:10.5px;opacity:.65;text-transform:uppercase;letter-spacing:.04em}.sfoot .who b{font-size:13.5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.shell.col:not(.nosb) .sfoot .who{justify-content:center;padding:6px 0}.shell.col:not(.nosb) .sfoot .who .t{display:none}
table.act{width:100%;border-collapse:collapse;font-size:13.5px}table.act th{position:sticky;top:0;text-align:left;padding:8px 10px;background:var(--card);border-bottom:2px solid var(--line);font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:var(--muted)}
table.act td{padding:9px 10px;border-bottom:1px solid var(--line);vertical-align:top}table.act td.w{white-space:nowrap;color:var(--muted);font-size:12.5px}
.act .who2{display:inline-flex;align-items:center;gap:6px;font-weight:700}.act .who2 i{width:22px;height:22px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-style:normal;font-size:11px;font-weight:800;color:#fff;background:var(--c,#3b5bdb)}
.act .chgs div{margin:2px 0}.act .chgs s{color:#d6336c;opacity:.85}.act .chgs b.n{color:#0b8a4f}.act .cmt{margin-top:4px;padding:6px 9px;border-left:3px solid var(--accent);background:rgba(127,127,127,.08);border-radius:0 8px 8px 0}
.act .ev{color:var(--muted);font-style:italic}.act button.dl{background:none;border:0;color:var(--accent);font-weight:700;cursor:pointer;padding:0}
.actbar{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px}.actap{padding:8px 16px;border-radius:8px;border:0;background:var(--accent);color:#fff;font-weight:700;cursor:pointer}
.actd{display:flex;align-items:center;gap:6px;font-size:12.5px;font-weight:600;color:var(--muted)}.actq{display:inline-flex;gap:6px}.actq button{padding:7px 10px}
.actbar select,.actbar input{padding:8px 10px;border-radius:8px;border:1px solid var(--line);background:var(--card);color:var(--text)}
.fld.need label,.fld.need .lbl{color:#d6336c}.fld.need input,.fld.need textarea,.fld.need select{border-color:#d6336c!important}
.mhead h2{margin:0;font-size:20px}
.mhead .msub{font-size:13px;opacity:.9}
.mhead button{background:rgba(255,255,255,.18);border:0;color:#fff;padding:6px 11px}
.mtools{padding:10px 22px 0}
.mtools input{width:100%;padding:8px 12px;font:inherit;color:var(--text);background:var(--bg);border:1px solid var(--line);border-radius:8px}
.mbody{padding:6px 22px 14px;overflow:auto;flex:1}
.msec{margin:16px 0 8px;font-size:12.5px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:var(--muted)}
.mgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:10px 16px}
.fld{min-width:0}
.fld label,.fld .lbl{display:block;font-size:12.5px;color:var(--muted);margin-bottom:3px}
.fld .val{padding:7px 10px;background:var(--bg);border:1px solid var(--line);border-radius:8px;min-height:35px;overflow-wrap:anywhere;white-space:pre-wrap}
.fld.wide{grid-column:1/-1}
.fld.ed input,.fld.ed select,.fld.ed textarea{width:100%;padding:7px 10px;font:inherit;color:var(--text);background:var(--card);border:1.5px solid #58d8cf;border-radius:8px}
.fld.ed textarea{min-height:70px;resize:vertical}
.fld.chg input,.fld.chg select,.fld.chg textarea{border-color:#f08c00;background:color-mix(in srgb,#f08c00 10%,var(--card))}
.fld.chg label::after{content:" • changed";color:#f08c00;font-weight:700}
.fld.hide{display:none}
.hist{width:100%;min-height:110px;padding:8px 10px;font:13px/1.45 ui-monospace,Consolas,monospace;color:var(--text);background:var(--bg);border:1px solid var(--line);border-radius:8px;resize:vertical}
.mfoot{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:space-between;padding:12px 22px;border-top:1px solid var(--line)}
.mfoot button.ghost{margin-right:6px}
#mBanner{margin:12px 22px 0}
.toast{position:fixed;bottom:22px;left:50%;transform:translateX(-50%);z-index:60;background:#0b6b46;color:#fff;padding:11px 20px;border-radius:10px;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.3)}
.toast[hidden]{display:none}
.wrap.full{max-width:none;padding:10px 16px}
.wrap.full header{display:none}
.wrap.full .tablewrap{max-height:calc(100vh - 290px)}
.back{display:inline-block;margin-bottom:10px;color:var(--text);text-decoration:none;font-size:14px}
.back:hover{text-decoration:underline}
.dashtitle{font-size:18px;margin:0 0 12px}
/* ---- app shell ---- */
.shell{display:flex;min-height:100vh}
.main{flex:1;min-width:0}
.side{width:250px;transition:width .22s ease;flex:none;position:sticky;top:0;height:100vh;overflow:auto;background:linear-gradient(180deg,#0e1a3f,#0b1330);color:#c7d0ee;display:flex;flex-direction:column;padding:16px 12px;z-index:40}
.side a{color:inherit;text-decoration:none}
.brand{display:flex;align-items:center;gap:10px;font-weight:700;font-size:17px;color:#fff!important;padding:4px 8px 16px}
.logo{width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.1);font-size:20px;display:inline-flex;align-items:center;justify-content:center;font-weight:700}
.logo .sbm{display:flex;height:22px;gap:3px;align-items:flex-end}.logo .sbm i{display:block;width:5px;border-radius:2.5px;background:linear-gradient(180deg,#75e8e0,#21bbb0);transform-origin:bottom;animation:lgBar 1.5s ease-in-out infinite alternate}
.logo .sbm i:nth-child(2){animation-delay:.15s}.logo .sbm i:nth-child(3){animation-delay:.3s}.logo .sbm i:nth-child(4){animation-delay:.45s;background:linear-gradient(180deg,#faceac,#c86e29)}
@keyframes lgBar{from{transform:scaleY(.5)}to{transform:scaleY(1)}}
.logo .sbm i:nth-child(1){height:9px}.logo .sbm i:nth-child(2){height:16px}.logo .sbm i:nth-child(3){height:12px}.logo .sbm i:nth-child(4){height:22px}
.logo:hover .sbm i{animation-duration:.6s}
.logo:hover{background:rgba(255,255,255,.2)}
.nav{display:flex;flex-direction:column;gap:2px}
.nav a{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;font-size:14.5px}
.nav a i{font-style:normal;width:22px;text-align:center;font-size:16px}
.nav a:hover,.unav a:hover{background:rgba(255,255,255,.08)}
.nav a.on{background:#2563eb;color:#fff}
.bd{margin-left:auto;background:#e03131;color:#fff;border-radius:999px;font-size:11.5px;font-weight:700;padding:1px 7px}
.bd:empty{display:none}
.navh{display:flex;justify-content:space-between;align-items:center;width:calc(100% - 0px);margin:14px 0 4px;padding:9px 12px;border-radius:10px;border:0;background:rgba(255,255,255,.06);font-size:12px;letter-spacing:.8px;text-transform:uppercase;color:#aab5dc;font-weight:700;cursor:pointer}
.navh:hover{background:rgba(255,255,255,.12)}
.navh .chev{transition:transform .2s;font-size:14px}
.navh[aria-expanded=true] .chev{transform:rotate(180deg)}
.logo{cursor:pointer;user-select:none}
.unav{overflow:auto;display:flex;flex-direction:column;gap:1px;max-height:calc(100vh - 380px);min-height:80px}
.unav a{display:flex;justify-content:space-between;gap:8px;padding:6px 12px;border-radius:8px;font-size:13.5px}
.urow{display:flex;align-items:center;gap:4px}.urow a{flex:1;min-width:0}
.uck{flex:none;width:15px;height:15px;margin:0 0 0 6px;accent-color:#5b7cfa;cursor:pointer;opacity:.55}.urow:hover .uck,.uck:checked{opacity:1}
.usel{position:sticky;top:0;z-index:2;display:flex;gap:6px;padding:6px 4px;background:#0e1a3f}.usel[hidden]{display:none}.usel .uopen{flex:1;background:#2563eb;color:#fff;border:0;padding:7px 10px;font-size:13px}.usel .uclr{background:rgba(255,255,255,.1);color:#c7d0ee;border:0;padding:7px 10px;font-size:13px}
.unav a span{color:#8a96be;font-variant-numeric:tabular-nums}
.unav a b{font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.unav a.on{background:rgba(37,99,235,.35);color:#fff}
.sfoot{margin:auto 0 0;padding-top:10px}
.sfoot button{width:100%;text-align:left;background:rgba(255,255,255,.07);border:0;color:#c7d0ee;font-weight:500}
.sbback{display:none}
.top{position:sticky;top:0;z-index:30;display:flex;align-items:center;gap:12px;padding:10px 20px;background:var(--card);border-bottom:1px solid var(--line)}
.top .menu{display:none;padding:8px 12px}
.shell.nosb .top .menu{display:inline-block}
.gs{position:relative;flex:1;max-width:560px}
.gs input{width:100%;padding:10px 14px;font:inherit;color:var(--text);background:var(--bg);border:1px solid var(--line);border-radius:10px}
.gsres{position:absolute;left:0;right:0;top:46px;background:var(--card);border:1px solid var(--line);border-radius:12px;box-shadow:0 12px 32px rgba(0,0,0,.2);overflow:hidden;z-index:60}
.gsi{display:flex;flex-direction:column;align-items:flex-start;gap:1px;width:100%;text-align:left;background:transparent;color:var(--text);border:0;border-radius:0;padding:9px 14px;font-weight:500;border-bottom:1px solid var(--line)}
.gsi:hover{background:rgba(59,91,219,.09)}
.gsi small{color:var(--muted);font-weight:400}
.gsall{display:block;padding:9px 14px;color:var(--accent);font-weight:600;text-decoration:none;font-size:13.5px}
.gsnone{padding:12px 14px;color:var(--muted)}
.tr{margin-left:auto;display:flex;align-items:center;gap:12px}
button.thm{width:38px;height:38px;padding:0;border-radius:12px;border:1px solid var(--line);background:var(--card);color:var(--text);display:grid;place-items:center;flex:none;line-height:0;box-shadow:var(--sh,none)}button.thm:hover{border-color:var(--accent);color:var(--accent)}button.thm svg{grid-area:1/1;transition:transform .35s,opacity .25s}button.thm .ico-moon{opacity:0;transform:rotate(-60deg) scale(.6)}html[data-theme=dark] button.thm .ico-sun{opacity:0;transform:rotate(60deg) scale(.6)}html[data-theme=dark] button.thm .ico-moon{opacity:1;transform:none}
.shell.nosb .side,.side.float{position:fixed;left:-280px;top:0;transition:left .2s}
body.sbopen .side{left:0!important;box-shadow:0 0 40px rgba(0,0,0,.4)}
body.sbopen .sbback{display:block;position:fixed;inset:0;background:rgba(10,15,35,.5);z-index:35}
.sidesp{display:none}
@media (min-width:901px){
  .shell:not(.nosb) .sidesp{display:block;flex:none;width:68px;transition:width .22s ease}
  .shell.pin:not(.nosb) .sidesp{width:250px}
  .shell:not(.nosb) .side{position:fixed;left:0;top:0}
  .shell:not(.nosb):not(.col):not(.pin) .side{box-shadow:0 0 0 1px rgba(255,255,255,.06),12px 0 60px rgba(10,32,30,.5)}
  .shell.col:not(.nosb) .side{width:68px;padding:16px 8px;overflow-x:hidden}
  .shell.col .side .t,.shell.col .unav{display:none}
  .shell.col .navh{justify-content:center;padding:9px 0;font-size:16px}
  .shell.col .navh .chev{display:none}
  .shell.col .brand{justify-content:center;padding:4px 0 16px}
  .shell.col .nav a{justify-content:center;padding:10px 0;position:relative}
  .shell.col .nav a i{width:auto;font-size:17px}
  .shell.col .bd{position:absolute;top:2px;right:6px;margin:0;padding:0 5px;font-size:10px}
  .shell.col .sfoot button{text-align:center;padding:8px 0}
  .side{scrollbar-width:thin}
}
@media (max-width:900px){
  .side{position:fixed;left:-280px;top:0;transition:left .2s}
  .top .menu{display:inline-block}
  .top{flex-wrap:wrap;padding:10px 12px}
  .tr{margin-left:0;width:100%;justify-content:space-between}
  .wrap{padding:12px}
}
/* ---- home ---- */
#home,#dash,#overview,#report{--s1:#2a78d6;--s2:#eb6834;--s3:#1baf7a;--s4:#eda100;--s5:#e87ba4;--s6:#008300;--s7:#316d69;--other:#98a2b3}
@media (prefers-color-scheme:dark){#home,#report{--s1:#3987e5;--s2:#d95926;--s3:#199e70;--s4:#c98500;--s5:#d55181;--s6:#008300;--s7:#63d1c9;--other:#667085}}
.hero{position:relative;display:flex;flex-wrap:wrap;align-items:center;gap:18px 32px;padding:24px 28px;border-radius:16px;color:#fff;background:radial-gradient(circle at 92% 20%,rgba(255,255,255,.18),transparent 34%),radial-gradient(circle at 70% 120%,rgba(36,172,163,.6),transparent 45%),linear-gradient(120deg,#1e3a8a,#2563eb);margin-bottom:16px;overflow:hidden}
.hero small{letter-spacing:2px;opacity:.8;font-size:12px}
.hero h1{margin:2px 0;font-size:30px}
.hero p{margin:0;opacity:.92}
.ht{flex:1 1 260px}
.hs{display:flex;flex-wrap:wrap;gap:22px}
.hs div{display:flex;align-items:center;gap:10px}
.hs i{font-style:normal;width:42px;height:42px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,.2);font-size:18px}
.hs b{display:block;font-size:22px;line-height:1.1}
.hs span{font-size:12.5px;opacity:.9}
.hbtn{background:#fff;color:#1e3a8a;text-decoration:none;font-weight:700;padding:10px 16px;border-radius:10px;font-size:14px}
.hk{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;margin-bottom:16px}
a.tile{display:block;position:relative;padding:16px 18px;border-radius:14px;background:var(--card);border:1px solid var(--line);color:var(--text);text-decoration:none;overflow:hidden}
a.tile:hover{border-color:var(--c,var(--accent));box-shadow:0 6px 18px rgba(20,30,60,.1)}
.tile .tl{color:var(--muted);font-size:13.5px;display:flex;align-items:center;gap:8px}
.tile .tl i{width:26px;height:26px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-style:normal;background:color-mix(in srgb,var(--c) 16%,transparent);color:var(--c)}
.tile b{display:block;font-size:30px;margin:4px 0 2px;font-variant-numeric:tabular-nums}
.tile .ts{font-size:12.5px;color:var(--muted)}
.tile .up{color:#0b8a4f;font-weight:700}.tile .dn{color:#d6336c;font-weight:700}
.tile svg.sp{position:absolute;right:12px;top:12px;width:110px;height:40px;opacity:.9}
.sp .l{fill:none;stroke:var(--c);stroke-width:2}.sp .a{fill:var(--c);opacity:.12}
.hcharts{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,380px),1fr));gap:14px;margin-bottom:22px}
.chh{display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:10px}
.chh h3{margin:0}
.chh select,.uctl select{padding:6px 10px;font:inherit;font-size:13px;color:var(--text);background:var(--card);border:1px solid var(--line);border-radius:8px}
.donutwrap{display:flex;flex-wrap:wrap;gap:14px;align-items:center}
svg.donut{width:160px;height:160px;flex:none}
.donut .seg{transition:stroke-width .12s}.donut a:hover .seg{stroke-width:7}
.donut .dn2{font-size:6.2px;font-weight:700;fill:var(--text)}.donut .dl{font-size:2.8px;fill:var(--muted)}
.legend{flex:1 1 150px;min-width:0;display:flex;flex-direction:column;gap:3px}
.li{display:flex;gap:8px;align-items:center;color:var(--text);text-decoration:none;font-size:13.5px;padding:3px 6px;border-radius:6px}
a.li:hover{background:rgba(127,127,127,.1)}
.li .dot{width:10px;height:10px;border-radius:50%;flex:none}
.li .ll{flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.li .ln{font-variant-numeric:tabular-nums}.li em{font-style:normal;color:var(--muted);font-size:12px}
.uctl{display:flex;flex-wrap:wrap;gap:8px;align-items:center}
.uctl input{flex:1 1 200px;min-width:0;padding:8px 12px;font:inherit;color:var(--text);background:var(--card);border:1px solid var(--line);border-radius:8px}
.seg2{display:inline-flex;border:1px solid var(--line);border-radius:8px;overflow:hidden}
.seg2 button{background:var(--card);color:var(--text);border:0;border-radius:0;padding:7px 12px}
.seg2 button.on{background:var(--accent);color:var(--accent-t)}
.unihead{margin-top:6px}
.ugrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:14px}
a.ucard{display:block;padding:14px 16px;border-radius:14px;background:var(--card);border:1px solid var(--line);color:var(--text);text-decoration:none}
a.ucard:hover{border-color:var(--accent);box-shadow:0 6px 18px rgba(20,30,60,.1)}
.ucard .uh{display:flex;align-items:center;gap:12px}
.ucard .av{width:42px;height:42px;flex:none;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:14px;background:color-mix(in srgb,var(--c) 20%,transparent);border:1px solid color-mix(in srgb,var(--c) 45%,transparent)}
.ucard .un{min-width:0;flex:1}
.ucard .un b{display:block;font-size:15px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ucard .un small{color:var(--muted);font-size:12.5px}
.ucard .nm{display:flex;justify-content:space-between;align-items:baseline;margin:10px 0 6px}
.ucard .nm b{font-size:18px;color:var(--accent)}
.ucard .nm span{font-size:12.5px;font-weight:700;color:#0b8a4f}
.ucard .pb{height:6px;border-radius:3px;background:rgba(127,127,127,.15);overflow:hidden}
.ucard .pb i{display:block;height:100%;background:linear-gradient(90deg,#3b5bdb,#2baea6);border-radius:3px}
.ucard .pt{margin-top:5px;font-size:12.5px;color:var(--muted);display:flex;justify-content:space-between}
.ucard .od{color:#d6336c;font-weight:700}
.ugrid.list{grid-template-columns:1fr;gap:8px}
.ugrid.list a.ucard{display:grid;grid-template-columns:minmax(200px,2fr) 1fr 2fr 1fr;align-items:center;gap:14px}
.ugrid.list .nm{margin:0;justify-content:flex-start;gap:8px}
.ugrid.list .pt{margin:0;justify-content:flex-end}
.hk .tile.warn{--c:#e03131}
a.kpi{text-decoration:none;color:var(--text)}
a.kpi:hover{box-shadow:0 6px 18px rgba(20,30,60,.1)}
/* ---- reports ---- */
.rcards{display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:14px}
a.rcard{display:flex;gap:14px;align-items:flex-start;padding:18px;border-radius:14px;background:var(--card);border:1px solid var(--line);color:var(--text);text-decoration:none}
a.rcard:hover{border-color:var(--accent);box-shadow:0 6px 18px rgba(20,30,60,.1)}
.rcard .ri{font-size:26px;width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:rgba(59,91,219,.12);flex:none}
.rcard b{font-size:16px}.rcard p{margin:4px 0 8px;color:var(--muted);font-size:13.5px}.rcard .go{color:var(--accent);font-weight:700;font-size:13.5px}
.sheethead .sub2{color:rgba(255,255,255,.92);font-size:14px;margin-top:3px;max-width:900px}
.rpf{display:flex;flex-wrap:wrap;align-items:flex-end;gap:10px 14px;background:var(--card);border:1px solid var(--line);border-radius:12px;padding:12px 14px;margin-bottom:12px}
.rpf .rpt{flex:1 0 100%;font-size:12px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:var(--muted)}
.rpf label{display:flex;flex-direction:column;gap:3px;font-size:12px;font-weight:600;color:var(--muted);min-width:150px;flex:1 1 150px}
.rpf select{padding:8px 9px;font:inherit;font-size:14px;color:var(--text);background:var(--bg);border:1px solid var(--line);border-radius:8px;max-width:100%}
.rpf select.on{border-color:#58d8cf;background:color-mix(in srgb,#58d8cf 12%,var(--card))}
.rpbar{display:flex;flex-wrap:wrap;gap:10px;justify-content:space-between;align-items:center;margin-bottom:10px}
.rpscroll{overflow:auto;background:var(--card);border-radius:12px;box-shadow:0 4px 16px rgba(20,30,60,.08);max-height:76vh}
table.rp{border-collapse:separate;border-spacing:0;width:auto;min-width:100%;font-size:13.5px}
table.rp th,table.rp td{padding:4px 6px;text-align:center;white-space:nowrap;max-width:none;border-bottom:1px solid var(--line);overflow:visible;cursor:default}
table.rp thead th{background:#285d59;color:#fff;border-bottom:1px solid rgba(255,255,255,.18);position:static;padding:9px 12px}
table.rp thead th.cg{background:#316d69;border-left:1px solid rgba(255,255,255,.3)}
table.rp thead th.sub{background:#326d69}
table.rp td.l,table.rp th.l{position:sticky;left:0;z-index:2;text-align:left;min-width:230px;max-width:340px;background:var(--card);overflow:hidden;text-overflow:ellipsis}
table.rp thead th.l{background:#285d59;z-index:3}
table.rp tr.grp td{background:color-mix(in srgb,#2baea6 10%,var(--card));font-weight:700}
table.rp td.ind{padding-left:28px}
table.rp td.st{background:rgba(59,91,219,.07);font-weight:700}
table.rp tr.grp td.st{background:color-mix(in srgb,#2baea6 16%,var(--card))}
table.rp tr.gt td{background:color-mix(in srgb,#2baea6 16%,var(--card));font-weight:800;border-top:2px solid var(--line)}
table.rp td.zero span{color:var(--muted);opacity:.5}
table.rp button.mxb{width:100%;min-width:44px;text-align:center}
table.rp td.l button.mxb{text-align:left;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block}
table.rp button.mxb.h{background:transparent;color:inherit;font-weight:inherit}
table.rp button.mxb.h:hover{border-color:var(--accent);background:rgba(59,91,219,.12)}
button.mxb.heat{display:block;width:100%;padding:7px 4px;border-radius:8px;border:0;font-weight:700;color:var(--text);background:rgba(59,91,219,var(--i))}
button.mxb.heat.dk{color:#fff}
button.mxb.heat:hover{outline:2px solid #2baea6;outline-offset:-2px}
.cb{position:relative}
.cbb{width:100%;display:flex;justify-content:space-between;align-items:center;gap:8px;padding:8px 10px;font:inherit;font-size:14px;font-weight:500;color:var(--text);background:var(--bg);border:1px solid var(--line);border-radius:8px;text-align:left;cursor:pointer}
.cbb.on{border-color:#58d8cf;background:color-mix(in srgb,#58d8cf 12%,var(--card))}
.cbt{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.cbc{color:var(--muted);flex:none}
.cbp{position:absolute;z-index:25;left:0;top:calc(100% + 4px);width:max(100%,270px);background:var(--card);border:1px solid var(--line);border-radius:10px;box-shadow:0 12px 30px rgba(0,0,0,.22);padding:8px}
.cbp input{width:100%;padding:8px 10px;font:inherit;font-size:14px;color:var(--text);background:var(--bg);border:1px solid var(--line);border-radius:8px;margin-bottom:6px}
.cbl{max-height:250px;overflow:auto}
.cbo{padding:7px 10px;border-radius:6px;cursor:pointer;font-size:14px;font-weight:500;color:var(--text)}
.cbo:hover,.cbo.act{background:rgba(59,91,219,.14)}.cbo.sel{font-weight:700;color:var(--accent)}
.cbn{padding:8px 10px;color:var(--muted);font-size:14px}
.cbo.chk::before{content:'☐';margin-right:8px;color:var(--muted)}.cbo.chk.on::before{content:'☑';color:var(--accent)}
.cbf{display:flex;gap:6px;justify-content:space-between;margin-top:8px;padding-top:8px;border-top:1px solid var(--line)}.cbf button{padding:6px 10px;font-size:13px;white-space:nowrap}.cbp:has(.cbf){width:max(100%,330px)}.cbf .cbap{margin-left:auto}
/* ---- documents pending ---- */
.dgrid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,400px),1fr));gap:14px;margin:14px 0}
.dbr{margin:12px 0 14px}.dbl{font-weight:600;font-size:14px;margin-bottom:5px}.dbl small{color:var(--muted);font-weight:400;margin-left:8px}
.dbs{display:flex;height:30px;border-radius:8px;overflow:hidden;gap:2px}
.dbs a{display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;text-decoration:none;min-width:2px;overflow:hidden}
.dbs a:hover{filter:brightness(1.12)}
.dlg{display:flex;flex-wrap:wrap;gap:6px 16px;font-size:13px;margin-top:6px;color:var(--muted)}.dlg span{display:inline-flex;align-items:center;gap:6px}.dlg i{width:11px;height:11px;border-radius:3px;display:inline-block}
a.hbar{display:grid;grid-template-columns:minmax(110px,1.4fr) 2fr 52px;gap:10px;align-items:center;padding:6px;border-radius:8px;color:var(--text);text-decoration:none;font-size:13.5px}
a.hbar:hover{background:rgba(59,91,219,.1)}
.hbar .hl{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.hbar .hb{height:12px;border-radius:6px;background:rgba(127,127,127,.15);overflow:hidden}.hbar .hb i{display:block;height:100%;background:#e03131;border-radius:6px}
.hbar .hn{text-align:right;font-weight:700;font-variant-numeric:tabular-nums}
.rpnote{font-size:13px;color:var(--muted);margin:0 0 12px;line-height:1.5}.rpnote b{color:var(--text)}.rpnote .od{color:#d6336c;font-weight:700}
div.rpnote a.dn{color:inherit;font-weight:700;text-decoration:underline}
table.rp td a.dn{display:block;padding:7px 8px;border-radius:6px;color:inherit;text-decoration:none;font-weight:600}
table.rp td a.dn:hover{background:rgba(59,91,219,.14)}
table.rp td.l a.dn{text-align:left;overflow:hidden;text-overflow:ellipsis}
table.rp tr.gt a.dn{font-weight:800}
.pb2{position:relative;height:24px;min-width:110px;background:rgba(224,49,49,.1);border-radius:6px;overflow:hidden}
.pb2 i{position:absolute;top:0;bottom:0;left:0;background:rgba(224,49,49,.38)}
.pb2 span{position:relative;font-weight:700;line-height:24px}
.chartcard.wide .rpscroll{box-shadow:none;border:1px solid var(--line)}
.chartcard.wide table.rp thead th{padding:9px 8px;font-size:13px}
.chartcard.wide table.rp td.l,.chartcard.wide table.rp th.l{min-width:170px}
.chartcard.wide .pb2{min-width:84px}
.pb2.g{background:rgba(27,175,122,.12)}.pb2.g i{background:rgba(27,175,122,.42)}
.hbar .hb i.gi{background:var(--s3)}
.hbar.hb2{grid-template-columns:70px 1fr 84px}
.hbar.hb2 .hb{position:relative}.hbar.hb2 .hb i{position:absolute;left:0;top:0;bottom:0}
.hbar .hb i.li2{background:color-mix(in srgb,var(--s1) 40%,transparent)}
.hsub{margin:-2px 0 6px 6px;font-size:12px;color:var(--muted)}
.sgt select{padding:4px 8px;font:inherit;font-size:13px;color:var(--text);background:var(--card);border:1px solid var(--line);border-radius:8px;max-width:190px}
#sgAuto{margin-top:10px}
.rnav{max-height:none}
[hidden]{display:none!important}
/* ---- plain tables: light borders, numbers only ---- */
:root{--tb:#d3d8e0;--tbh:#98a2b3;--pop:#3b5bdb}
@media (prefers-color-scheme:dark){:root{--tb:#3a4250;--tbh:#66728a;--pop:#8ea4ff}}
table.mx,table.rp,#dash table{border-collapse:collapse!important;border-spacing:0!important}
table.mx th,table.mx td,table.rp th,table.rp td,#dash th,#dash td{border:1px solid var(--tb)!important;background:var(--card)!important;box-shadow:none!important;background-image:none!important}
table.mx th,table.rp th,#dash th{color:var(--text)!important;font-weight:700;border-bottom:2px solid var(--tbh)!important}
table.mx tbody tr,table.rp tbody tr,#dash tbody tr{background:none!important}
table.mx thead button.mxb,table.rp thead button.mxb{color:var(--text)!important}
button.mxb.heat,button.mxb.heat.dk{background:transparent!important;color:var(--text)!important;border-radius:6px;outline:0!important;font-weight:600}
table.mx tbody tr:last-child td,table.rp tr.gt td{border-top:2px solid var(--tbh)!important;font-weight:800}
table.rp td.st,table.rp tr.grp td{font-weight:700}
.pb2,.pb2.g{background:none!important;min-width:0}.pb2 i{display:none}
.tb i{display:none}
.pill{background:none!important;border:0!important;padding:0!important;color:inherit}.pill i{display:none}
.cd{display:none}
.rpscroll,.tablewrap,.mxscroll,#dash .tablewrap,.chartcard.wide .rpscroll{border:1px solid var(--tb)!important;border-radius:10px!important;box-shadow:none!important}
/* hover: the number pops and takes a colour */
button.mxb,table.rp td a.dn,table.mx a{transition:transform .14s ease,color .14s ease,background-color .14s ease,box-shadow .14s ease}
button.mxb.heat:hover,button.mxb.h:hover,table.rp td a.dn:hover,table.mx td a:hover{transform:scale(1.28);color:var(--pop)!important;font-weight:800;background:color-mix(in srgb,var(--pop) 14%,transparent)!important;box-shadow:0 2px 10px color-mix(in srgb,var(--pop) 35%,transparent)!important;border-radius:8px;position:relative;z-index:1}
table.rp td.l a.dn:hover,table.rp td.l button.mxb:hover{transform:scale(1.06);transform-origin:left center}
#dash tbody tr:hover td{color:var(--text)}
#dash tbody td.num:hover{color:var(--pop);font-weight:800;transform:scale(1.12);transition:transform .14s ease,color .14s ease}
a.lnk{cursor:pointer}a.lnk:hover text{fill:var(--pop,#3b5bdb);font-weight:800}a.lnk:hover circle.pt{stroke-width:3;r:8}
svg.stk a{cursor:pointer}svg.stk a:hover .seg{filter:brightness(1.12)}
.gfbar{display:flex;flex-wrap:wrap;align-items:flex-end;gap:10px 12px;padding:10px 20px;background:var(--card);border-bottom:1px solid var(--line)}
.gfbar[hidden]{display:none}
.gfbar .gft{font-size:12px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:var(--muted);align-self:center}
.gfbar label{display:flex;flex-direction:column;gap:3px;font-size:12px;font-weight:600;color:var(--muted);min-width:140px;flex:1 1 140px}
.gfbar .gfs{align-self:center;font-size:13px;font-weight:700;color:var(--accent)}
.gfbar .ghost{align-self:flex-end}
.gfbar.on{box-shadow:inset 0 -3px 0 #58d8cf}
.rfbar{position:fixed;top:0;left:0;right:0;height:4px;z-index:500;background:rgba(127,127,127,.18)}.rfbar i{display:block;height:100%;width:0;background:linear-gradient(90deg,#4c6ef5,#ac6a37);transition:width .45s ease}
.uleft{display:flex;align-items:center;gap:14px;flex-wrap:wrap}.uleft h2{margin:0}
.mtwo{display:grid;grid-template-columns:1fr 1fr;gap:10px 18px}.mcol{display:flex;flex-direction:column;gap:10px;min-width:0}@media(max-width:760px){.mtwo{grid-template-columns:1fr}}
.ro{font-style:normal;font-size:10.5px;font-weight:600;color:var(--muted);opacity:.85;margin-left:4px;text-transform:none;letter-spacing:0}.mcol .hist{min-height:150px}
button.mxb.tn{font-weight:800;padding:4px 8px;min-width:0}.tb button.mxb.tn{display:inline-block;margin-right:6px}
.nav a.subnav{margin-left:14px;padding:6px 12px;font-size:13.5px;opacity:.92}.nav a.subnav i{font-size:14px}
/* ---- subtle motion ---- */
@keyframes mvIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
@keyframes mvPop{from{opacity:0;transform:translateY(14px) scale(.97)}to{opacity:1;transform:none}}
@keyframes mvFade{from{opacity:0}to{opacity:1}}
@keyframes mvGrow{from{transform:scaleX(0)}to{transform:scaleX(1)}}
@keyframes mvRise{from{transform:scaleY(0)}to{transform:scaleY(1)}}
main>section:not([hidden]),.wrap>section:not([hidden]){animation:mvIn .32s ease-out}
.kpis>.kpi,a.tile,.charts>*{animation:mvIn .4s ease-out backwards}
.kpis>*:nth-child(2),a.tile:nth-child(2),.charts>*:nth-child(2){animation-delay:.05s}
.kpis>*:nth-child(3),a.tile:nth-child(3),.charts>*:nth-child(3){animation-delay:.1s}
.kpis>*:nth-child(4),a.tile:nth-child(4),.charts>*:nth-child(4){animation-delay:.15s}
.kpis>*:nth-child(5),a.tile:nth-child(5){animation-delay:.2s}
.kpis>*:nth-child(6),a.tile:nth-child(6){animation-delay:.25s}
.kpis>*:nth-child(n+7),a.tile:nth-child(n+7){animation-delay:.3s}
.kpi,a.tile,a.ucard{transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}
a.kpi:hover,a.tile:hover,a.ucard:hover{transform:translateY(-3px)}
a.kpi:hover{box-shadow:0 8px 20px rgba(20,30,60,.12)}
.ucard .pb i,.hbar .hb i{transform-origin:left;animation:mvGrow .7s cubic-bezier(.2,.7,.2,1) backwards .1s}
.stk .seg{transform-box:fill-box;transform-origin:left;animation:mvGrow .6s cubic-bezier(.2,.7,.2,1) backwards}
.stk .ln1{stroke-dasharray:2000;animation:mvDraw 1.2s ease-out}
@keyframes mvDraw{from{stroke-dashoffset:2000}to{stroke-dashoffset:0}}
.modal:not([hidden]){animation:mvFade .18s ease-out}
.modal:not([hidden]) .mbox{animation:mvPop .26s cubic-bezier(.2,.8,.2,1)}
button,.btnlink{transition:transform .12s ease,filter .15s ease,background-color .15s ease,border-color .15s ease}
button:active:not(:disabled),.btnlink:active{transform:scale(.97)}
tbody tr{transition:background-color .12s ease}
@media (prefers-reduced-motion:reduce){main>section,.wrap>section,.kpis>*,a.tile,.charts>*,.ucard .pb i,.hbar .hb i,.stk .seg,.stk .ln1,.modal,.mbox{animation:none!important}a.kpi:hover,a.tile:hover,a.ucard:hover{transform:none}}
/* ===== THEME 2: matches the login page ===== */
:root{--bg:#d7ece8;--line:#b4d6d0;--accent:#10978c;--accent-t:#fff;--sh:0 10px 28px rgba(31,95,90,.18),0 2px 6px rgba(31,95,90,.10);--sh2:0 18px 40px rgba(31,95,90,.26)}
@media (prefers-color-scheme:dark){:root{--bg:#0d1313;--card:#141d1c;--line:#1f2e2d;--accent:#72eee5;--accent-t:#0d1b1a;--sh:0 10px 30px rgba(0,0,0,.35);--sh2:0 18px 40px rgba(0,0,0,.5)}}
body{background:var(--bg);font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif}
.shell{position:relative}
.shell::before,.shell::after{content:"";position:fixed;border-radius:50%;filter:blur(80px);opacity:.55;pointer-events:none;z-index:0;animation:thFloat 18s ease-in-out infinite}
.shell::before{width:420px;height:420px;left:240px;top:-140px;background:#8fdcd2}
.shell::after{width:380px;height:380px;right:-100px;bottom:-120px;background:#fee4d0;animation-delay:-7s}
@media (prefers-color-scheme:dark){.shell::before,.shell::after{opacity:.18}}
@keyframes thFloat{0%,100%{transform:translate(0,0)}50%{transform:translate(40px,-40px)}}
.main{position:relative;z-index:1}
button{border-radius:12px;border-color:transparent;background:linear-gradient(100deg,#0f9488,#18b3a6);color:#fff;box-shadow:0 6px 16px rgba(33,187,176,.28)}
button:hover:not(:disabled){filter:brightness(1.06);box-shadow:0 10px 22px rgba(33,187,176,.36)}
button.ghost{background:var(--card);color:var(--text);border:1px solid var(--line);box-shadow:none}
button.ghost:hover:not(:disabled){border-color:var(--accent);filter:none}
.chip.on,.seg2 button.on,button.mxb.on{background:linear-gradient(100deg,#0f9488,#18b3a6);border-color:transparent;color:#fff}
a.btnlink{background:linear-gradient(100deg,#0f9488,#18b3a6);border-radius:12px;box-shadow:0 6px 16px rgba(33,187,176,.28)}
input[type=search],select,.gs input{border-radius:12px}
input:focus,select:focus,.gs input:focus{outline:0;border-color:#2cdace;box-shadow:0 0 0 4px rgba(44,218,206,.16)}
/* top bar + filter bar */
.top{background:color-mix(in srgb,var(--card) 82%,transparent);backdrop-filter:blur(14px);-webkit-backdrop-filter:blur(14px);border-bottom:1px solid var(--line);padding:12px 24px}
.gs input{background:var(--card);border:1.5px solid var(--line);padding:11px 70px 11px 16px;transition:border-color .2s,box-shadow .2s}
.gs .kbd{position:absolute;right:12px;top:50%;transform:translateY(-50%);padding:3px 8px;border:1px solid var(--line);border-radius:8px;background:var(--bg);color:var(--muted);font-size:11.5px;font-weight:700;pointer-events:none;transition:opacity .2s}
.gs:focus-within .kbd{opacity:0}
.gsres{border-radius:16px;box-shadow:var(--sh2)}
.gfbar{background:var(--card);margin:12px 24px 0;border:1px solid var(--line);border-radius:18px;box-shadow:var(--sh);padding:12px 18px}
.gfbar.on{box-shadow:var(--sh),inset 0 -3px 0 #75e8e0}
.wrap{padding:20px 24px 40px;position:relative;z-index:1}
/* sidebar: light + violet */
@media not (prefers-color-scheme:dark){
 .side{background:linear-gradient(180deg,#ffffff,#e6f5f3);color:#374847;border-right:1px solid var(--line)}
 .brand{color:#121d1c!important}
 .logo{background:#ecfcfb;color:#21bbb0}.logo:hover{background:#def9f7}
 .nav a:hover,.unav a:hover{background:#effdfc}
 .nav a.on{background:#bfe8e2;color:#0b6f66;font-weight:700;box-shadow:inset 3px 0 0 #10978c}
 .navh{background:#e1f3f0;color:#3f6f6b}.navh:hover{background:#cdebe6}
 .unav a span{color:#627e7c}
 .unav a.on{background:#bfe8e2;color:#0b6f66}
 .usel{background:#e6f5f3}.usel .uopen{background:linear-gradient(100deg,#0f9488,#18b3a6)}.usel .uclr{background:#cdebe6;color:#0b6f66;box-shadow:none}
 .sfoot button{background:#dcf0ed;color:#0b6f66;box-shadow:none}
 .sfoot .who{background:#dcf0ed;color:#121d1c}
 .uck{accent-color:#21bbb0}
}
@media (prefers-color-scheme:dark){.side{background:linear-gradient(180deg,#101c1b,#0b1514)}.nav a.on{background:linear-gradient(100deg,#0f9488,#18b3a6)}}
.nav a{transition:background .18s,transform .18s,padding .18s}
.nav a:hover{transform:translateX(3px)}
.nav a i{transition:transform .25s}.nav a:hover i{transform:scale(1.2) rotate(-6deg)}
.sfoot .who .av{background:linear-gradient(135deg,#21bbb0,#f5ae77)}
.bd{background:linear-gradient(135deg,#f43f5e,#e11d48);animation:thPulse 2.4s ease-in-out infinite}
@keyframes thPulse{0%,100%{box-shadow:0 0 0 0 rgba(244,63,94,.45)}50%{box-shadow:0 0 0 6px rgba(244,63,94,0)}}
/* hero */
.hero{position:relative;display:block;min-height:210px;padding:30px 34px;border-radius:24px;color:#0b2f2c;background:radial-gradient(circle at 12% 0%,rgba(255,255,255,.45),transparent 40%),linear-gradient(120deg,#a6e3db 0%,#6fd2c7 48%,#f6bf93 100%);background-size:140% 140%;box-shadow:var(--sh);margin-bottom:20px;overflow:hidden;animation:mvIn .5s ease-out both,thGrad 14s ease-in-out infinite alternate}
@keyframes thGrad{to{background-position:100% 100%}}
@media (prefers-color-scheme:dark){.hero{color:#fff;background:radial-gradient(circle at 12% 0%,rgba(255,255,255,.1),transparent 40%),linear-gradient(120deg,#1c4542,#266460 55%,#88542b)}}
.hero .ht{position:relative;z-index:2;max-width:46%}
.hero .hello{font-size:19px;opacity:.75;animation:mvIn .6s .1s both}
.hero h1{margin:2px 0 6px;font-size:46px;line-height:1.05;letter-spacing:-1.4px;font-weight:800;animation:mvIn .6s .2s both}
.hero .wave{display:inline-block;transform-origin:70% 70%;animation:lgWave 2.6s 1.2s ease-in-out infinite}
@keyframes lgWave{0%,60%,100%{transform:rotate(0)}10%{transform:rotate(16deg)}20%{transform:rotate(-10deg)}30%{transform:rotate(16deg)}40%{transform:rotate(-6deg)}50%{transform:rotate(10deg)}}
.hero p{margin:0;font-size:15.5px;opacity:.85;animation:mvIn .6s .3s both}
.hero .hbtn{display:inline-block;margin-top:16px;background:#fff;color:#28958e;border-radius:12px;box-shadow:0 8px 20px rgba(33,187,176,.22);transition:transform .2s,box-shadow .2s;animation:mvIn .6s .4s both}
.hero .hbtn:hover{transform:translateY(-2px);box-shadow:0 14px 28px rgba(33,187,176,.32)}
.hero .hs{position:absolute;z-index:3;right:28px;top:50%;transform:translateY(-50%);display:flex;flex-direction:column;gap:12px;align-items:stretch}
.hero .hs div{display:flex;align-items:center;gap:12px;min-width:190px;padding:10px 16px 10px 12px;border-radius:16px;background:rgba(255,255,255,.88);backdrop-filter:blur(8px);box-shadow:0 12px 26px rgba(51,117,112,.2);color:#121d1c;animation:thChip 6s ease-in-out infinite,mvPop .6s both}
.hero .hs div:nth-child(2){animation-delay:-2s,.15s;margin-left:26px}.hero .hs div:nth-child(3){animation-delay:-4s,.3s}
@keyframes thChip{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
.hero .hs i{width:38px;height:38px;border-radius:12px;background:#c6ece7;font-size:18px}
.hero .hs b{font-size:21px;color:#121d1c}.hero .hs span{opacity:1;color:#576a69;font-size:12.5px}
@media (prefers-color-scheme:dark){.hero .hs div{background:rgba(20,29,28,.85);color:#fff}.hero .hs b{color:#fff}.hero .hs i{background:#27504d}}
.hscene{position:absolute;inset:0 0 0 30%;pointer-events:none;--hx:0;--hy:0}
.hscene .cl{position:absolute;height:auto;aspect-ratio:200/80;fill:#fff;opacity:.8}
.hscene .c1{width:150px;left:4%;top:12%;animation:lgDrift 24s linear infinite alternate}
.hscene .c2{width:120px;left:50%;top:6%;opacity:.6;animation:lgDrift 30s linear infinite alternate-reverse}
.hscene .c3{width:170px;left:52%;bottom:-18px;opacity:.7;animation:lgDrift 28s linear infinite alternate}
@keyframes lgDrift{from{transform:translateX(-18px)}to{transform:translateX(26px)}}
.hscene .stack{position:absolute;left:27%;bottom:6px;width:270px;transform:translate(calc(var(--hx)*14px),calc(var(--hy)*8px));transition:transform .25s ease-out}
.hscene .stack svg{width:100%;height:auto;overflow:visible;animation:thBob 6s ease-in-out infinite;filter:drop-shadow(0 18px 18px rgba(30,96,91,.28))}
@keyframes thBob{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.hscene .tas{transform-origin:86% 40%;animation:thSwing 3s ease-in-out infinite alternate}
@keyframes thSwing{from{transform:rotate(-10deg)}to{transform:rotate(10deg)}}
.hscene .bk{transform-box:fill-box;transform-origin:left center;animation:thBook .9s cubic-bezier(.3,1.3,.4,1) both}
.hscene .bk2{animation-delay:.15s}.hscene .bk3{animation-delay:.3s}.hscene .cap{animation:mvPop .8s .5s both}
@keyframes thBook{from{opacity:0;transform:translateX(-30px) scaleX(.6)}to{opacity:1;transform:none}}
.hscene .spk{position:absolute;width:14px;height:14px;background:#2cdace;clip-path:polygon(50% 0,62% 38%,100% 50%,62% 62%,50% 100%,38% 62%,0 50%,38% 38%);opacity:0;animation:lgTw 3.4s ease-in-out infinite}
@keyframes lgTw{0%,100%{opacity:0;transform:scale(.3) rotate(0)}50%{opacity:.9;transform:scale(1.2) rotate(90deg)}}
.hscene .bld{position:absolute;left:1%;bottom:-6px;width:240px;transform:translate(calc(var(--hx)*-10px),calc(var(--hy)*-5px));transition:transform .25s ease-out;animation:mvIn .8s .3s both}
.hscene .bld svg{display:block;width:100%;height:auto;overflow:visible}
.hscene .bld{width:232px;bottom:6px}
.hscene .cl,.hscene .orb,.hscene .trees{display:none!important}
.hscene .bld svg{overflow:hidden;filter:drop-shadow(0 10px 14px rgba(11,58,68,.35))!important}

.hscene .bld .flag{transform-box:fill-box;transform-origin:left center;animation:thFlag 2.2s ease-in-out infinite alternate}
@keyframes thFlag{from{transform:skewY(-8deg) scaleX(.85)}to{transform:skewY(8deg) scaleX(1)}}
.hscene .trees{position:absolute;left:8%;right:0;bottom:-4px;width:84%;height:auto;animation:mvIn .8s .5s both}
.hscene .mini{position:absolute;left:62%;top:14%;width:112px;padding:9px 11px 8px;border-radius:14px;background:rgba(255,255,255,.92);box-shadow:0 14px 28px rgba(51,117,112,.25);animation:thBob 5s ease-in-out infinite,mvPop .7s .6s both;transform:translate(calc(var(--hx)*18px),calc(var(--hy)*10px))}
.hscene .mini .mh{display:flex;gap:4px;margin-bottom:6px}.hscene .mini .mh i{width:6px;height:6px;border-radius:50%;background:#ffd1d1}.hscene .mini .mh i:nth-child(2){background:#ffe9b0}.hscene .mini .mh i:nth-child(3){background:#c9f2d3}
.hscene .mini .mb{display:flex;align-items:flex-end;gap:5px;height:38px}
.hscene .mini .mb b{flex:1;height:0;border-radius:4px 4px 2px 2px;background:linear-gradient(180deg,#9fefea,#2cdace);animation:lgGrow .8s cubic-bezier(.3,1.3,.4,1) forwards;animation-delay:calc(.9s + var(--d,0s))}
.hscene .mini .mb b:nth-child(4){background:linear-gradient(180deg,#f9d2b4,#c86e29)}
.hscene .mini .mb b:nth-child(2){--d:.08s}.hscene .mini .mb b:nth-child(3){--d:.16s}.hscene .mini .mb b:nth-child(4){--d:.24s}.hscene .mini .mb b:nth-child(5){--d:.32s}.hscene .mini .mb b:nth-child(6){--d:.4s}
@keyframes lgGrow{to{height:var(--h)}}
.hscene .mini .mt{margin-top:6px;font-size:11px;font-weight:800;color:#0b8a4f}
.hscene .orb{position:absolute;border-radius:50%;background:radial-gradient(circle at 30% 30%,#fff,rgba(255,255,255,.15));animation:thBob 7s ease-in-out infinite}
.hscene .o1{width:26px;height:26px;left:4%;top:62%;animation-delay:-1s}.hscene .o2{width:16px;height:16px;left:46%;top:20%;animation-delay:-3s}.hscene .o3{width:20px;height:20px;left:92%;top:28%;animation-delay:-5s}
/* night mode: make the animated scene and background glows clearly visible */
@media (prefers-color-scheme:dark){
.shell::before,.shell::after{opacity:.5;filter:blur(70px)}
.shell::before{background:#2ee5d9;width:480px;height:480px}
.shell::after{background:#ea8b42;width:440px;height:440px}
.hscene .cl{fill:#fff;opacity:1}.hscene .c2{opacity:.85}.hscene .c3{opacity:.9}
.hscene .trees{display:none!important}
.hscene .bld #thbg stop:first-child{stop-opacity:.95}.hscene .bld #thbg stop:last-child{stop-opacity:.5}
.hscene .bld svg{filter:drop-shadow(0 0 14px rgba(183,244,240,.55))}
.hscene .stack svg{filter:drop-shadow(0 0 26px rgba(117,232,224,.6))}
.hscene .spk{width:17px;height:17px;background:#fff;filter:drop-shadow(0 0 7px #b7f4f0);animation-name:lgTwN}
.hscene .orb{box-shadow:0 0 18px rgba(183,244,240,.85)}
.hscene .mini{background:rgba(22,40,38,.94);box-shadow:0 0 26px rgba(44,218,206,.45)}
.hscene .mini .mt{color:#4ade80}
}
@keyframes lgTwN{0%,100%{opacity:.15;transform:scale(.4) rotate(0)}50%{opacity:1;transform:scale(1.35) rotate(90deg)}}
body.mw-1000 .hscene .bld,body.mw-1000 .hscene .trees{display:none}body.mw-1000 .hscene .stack{left:18%}
body.mw-850 .hero .ht{max-width:100%}body.mw-850 .hscene{opacity:.5}body.mw-850 .hero .hs{position:static;transform:none;flex-direction:row;flex-wrap:wrap;margin-top:18px}body.mw-850 .hero .hs div{min-width:0;margin:0!important}body.mw-850 .hero h1{font-size:38px}
@media (max-width:700px){.hscene{display:none}.hero{padding:22px 20px}.wrap{padding:14px 14px 30px}.gfbar{margin:10px 14px 0}}
.navh,.sfoot button,.usel button,.logo{box-shadow:none!important}
.tile svg.sp{top:auto!important;bottom:16px;right:18px;opacity:.75}
.sheethead,.mhead{background:radial-gradient(circle at 90% 0%,rgba(255,255,255,.22),transparent 40%),linear-gradient(120deg,#21bbb0,#2cdace 55%,#c86e29);border-radius:20px;box-shadow:0 12px 28px rgba(33,187,176,.28);animation:mvIn .45s ease-out both}
/* cards */
a.tile{border-radius:20px;border:1px solid var(--line);box-shadow:var(--sh);padding:18px 20px;background:var(--card);transition:transform .22s,box-shadow .22s,border-color .22s}
a.tile::before{content:"";position:absolute;left:0;right:0;top:0;height:4px;background:linear-gradient(90deg,var(--c),transparent);opacity:.85}
a.tile:hover{transform:translateY(-5px);box-shadow:var(--sh2)}
.tile .tl i{width:34px;height:34px;border-radius:12px;font-size:16px;transition:transform .3s}
a.tile:hover .tl i{transform:scale(1.15) rotate(-8deg)}
.tile b{font-size:32px;letter-spacing:-.8px}
.chartcard,.kpi,a.ucard,a.rcard,.tablewrap,.dash,.ovcard{border-radius:20px;box-shadow:var(--sh);border-color:var(--line)}
.chartcard{padding:20px 22px;transition:box-shadow .22s,transform .22s}
.chartcard:hover{box-shadow:var(--sh2)}
.chartcard h3,.chh h3{letter-spacing:-.2px}
a.ucard,a.rcard{transition:transform .22s,box-shadow .22s,border-color .22s}
a.ucard:hover,a.rcard:hover{transform:translateY(-4px);box-shadow:var(--sh2);border-color:#75e8e0}
.ucard .av{border-radius:14px}
.unihead h2{font-size:20px;letter-spacing:-.4px}
.hk{gap:16px;margin-bottom:18px}
.hcharts{gap:16px}
.modal .mbox,.mbox{border-radius:22px}
.rfbar i{background:linear-gradient(90deg,#21bbb0,#f5ae77)}
::selection{background:#d2f9f7}
/* staggered rise for the tiles and chart cards on the home page */
#hKpis>a.tile:nth-child(1),#hKpis2>a.tile:nth-child(1){animation-delay:.05s}#hKpis>a.tile:nth-child(2),#hKpis2>a.tile:nth-child(2){animation-delay:.12s}#hKpis>a.tile:nth-child(3),#hKpis2>a.tile:nth-child(3){animation-delay:.19s}#hKpis>a.tile:nth-child(4),#hKpis2>a.tile:nth-child(4){animation-delay:.26s}#hKpis>a.tile:nth-child(5),#hKpis2>a.tile:nth-child(5){animation-delay:.33s}
.hcharts>.chartcard:nth-child(1){animation:mvIn .5s .3s both}.hcharts>.chartcard:nth-child(2){animation:mvIn .5s .4s both}.hcharts>.chartcard:nth-child(3){animation:mvIn .5s .5s both}
.ugrid>a.ucard{animation:mvIn .45s ease-out backwards}.ugrid>a.ucard:nth-child(2){animation-delay:.05s}.ugrid>a.ucard:nth-child(3){animation-delay:.1s}.ugrid>a.ucard:nth-child(4){animation-delay:.15s}.ugrid>a.ucard:nth-child(n+5){animation-delay:.2s}
@media (prefers-reduced-motion:reduce){.shell::before,.shell::after,.hero,.hero *,.hscene *,.bd,.nav a{animation:none!important;transition:none!important}}
/* ===== home layout (matches the new design) ===== */
#hKpis,#hKpis2{grid-template-columns:repeat(6,minmax(0,1fr))}
body.mw-960 #hKpis,body.mw-960 #hKpis2{grid-template-columns:repeat(4,minmax(0,1fr))}body.mw-640 #hKpis,body.mw-640 #hKpis2{grid-template-columns:repeat(3,minmax(0,1fr))}body.mw-480 #hKpis,body.mw-480 #hKpis2{grid-template-columns:repeat(2,minmax(0,1fr))}
@media (max-width:720px){#hKpis,#hKpis2{grid-template-columns:repeat(2,minmax(0,1fr))}.tile b{font-size:26px}}
.tile .ts{display:block;line-height:1.35}
.tile:has(svg.sp) .ts{max-width:calc(100% - 78px)}
.tile svg.sp{width:74px!important;height:34px!important}
#home>.gfbar{position:relative;z-index:30;margin:0 0 18px;animation:mvIn .45s .2s both}
.hgrid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:16px;margin-bottom:22px}
.g-trend{grid-column:span 5}.g-status{grid-column:span 4}.g-act{grid-column:span 3}
.g-prog{grid-column:span 4}.g-top{grid-column:span 5}.g-quick{grid-column:span 3}
body.mw-1030 .g-trend{grid-column:span 12}body.mw-1030 .g-status,body.mw-1030 .g-act{grid-column:span 6}body.mw-1030 .g-prog,body.mw-1030 .g-top{grid-column:span 6}body.mw-1030 .g-quick{grid-column:span 12}
@media (max-width:760px){.hgrid>*{grid-column:span 12!important}}
.hgrid>.chartcard{animation:mvIn .5s both}.g-trend{animation-delay:.25s!important}.g-status{animation-delay:.32s!important}.g-act{animation-delay:.39s!important}.g-prog{animation-delay:.46s!important}.g-top{animation-delay:.53s!important}.g-quick{animation-delay:.6s!important}
.chh .vall{font-size:13px;font-weight:700;color:var(--accent);text-decoration:none}.chh .vall:hover{text-decoration:underline}
/* ranked universities */
.rank{display:flex;flex-direction:column;gap:10px}
a.rk{display:grid;grid-template-columns:26px 38px minmax(0,1.3fr) auto minmax(70px,1.4fr);align-items:center;gap:10px;padding:8px 10px;border-radius:14px;color:var(--text);text-decoration:none;transition:background .18s,transform .18s}
a.rk:hover{background:color-mix(in srgb,var(--accent) 8%,transparent);transform:translateX(3px)}
a.rk .n{display:grid;place-items:center;width:24px;height:24px;border-radius:8px;background:var(--bg);color:var(--muted);font-size:12px;font-weight:800}
a.rk .av{display:grid;place-items:center;width:36px;height:36px;border-radius:12px;font-size:12.5px;font-weight:800;background:color-mix(in srgb,var(--c) 18%,transparent);color:var(--c);border:1px solid color-mix(in srgb,var(--c) 40%,transparent)}
a.rk .nm{font-weight:700;font-size:14px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
a.rk .ct{font-size:12.5px;color:var(--muted);font-variant-numeric:tabular-nums;white-space:nowrap}
a.rk .bar{height:8px;border-radius:6px;background:var(--bg);overflow:hidden}
a.rk .bar i{display:block;height:100%;border-radius:6px;background:linear-gradient(90deg,var(--c),color-mix(in srgb,var(--c) 55%,#fff));transform-origin:left;animation:mvGrow .9s cubic-bezier(.2,.7,.2,1) both .5s}
/* deals by program */
.vb{display:flex;align-items:flex-end;gap:12px;height:230px;padding-top:6px;background:repeating-linear-gradient(0deg,transparent 0 44px,color-mix(in srgb,var(--line) 70%,transparent) 44px 45px)}
a.vbc{flex:1;min-width:0;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;gap:6px;height:100%;color:var(--text);text-decoration:none}
a.vbc b{font-size:13.5px;font-variant-numeric:tabular-nums}
a.vbc i{display:block;width:78%;max-width:64px;height:calc(var(--h)*1.5px);min-height:4px;border-radius:10px 10px 4px 4px;background:linear-gradient(180deg,color-mix(in srgb,var(--c) 70%,#fff),var(--c));transform-origin:bottom;animation:mvRise .8s cubic-bezier(.3,1.2,.4,1) both;animation-delay:calc(.5s + var(--i)*.08s);transition:filter .2s,transform .2s}
a.vbc:hover i{filter:brightness(1.12);transform:scaleY(1.04)}
a.vbc span{font-size:12px;color:var(--muted);max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;padding-bottom:2px}
/* recent activity */
.ra{display:flex;flex-direction:column;gap:2px;position:relative}
.ra::before{content:"";position:absolute;left:19px;top:14px;bottom:14px;width:2px;background:linear-gradient(180deg,var(--line),transparent)}
button.ri{position:relative;display:grid;grid-template-columns:40px minmax(0,1fr) auto;align-items:center;gap:10px;width:100%;padding:8px 6px;text-align:left;background:transparent;border:0;border-radius:12px;color:var(--text);font-weight:500;box-shadow:none;animation:mvIn .4s both}
button.ri:hover{background:color-mix(in srgb,var(--accent) 8%,transparent);filter:none;box-shadow:none}
button.ri .ic{display:grid;place-items:center;width:38px;height:38px;border-radius:50%;font-size:16px;font-style:normal;background:color-mix(in srgb,var(--c) 18%,var(--card));border:2px solid var(--card);z-index:1}
button.ri b{display:block;font-size:13.5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
button.ri small{display:block;color:var(--muted);font-size:12px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
button.ri time{font-size:11.5px;color:var(--muted);white-space:nowrap}
/* quick actions */
.qa{display:grid;grid-template-columns:minmax(0,1fr);gap:10px}
body.mw-1030 .qa{grid-template-columns:repeat(4,minmax(0,1fr))}@media (max-width:760px){.qa{grid-template-columns:1fr 1fr}}
a.qb{min-width:0;display:flex;align-items:center;gap:10px;padding:12px;border-radius:14px;background:var(--bg);border:1px solid transparent;color:var(--text);text-decoration:none;transition:transform .2s,box-shadow .2s,border-color .2s}
a.qb:hover{transform:translateY(-3px);box-shadow:var(--sh);border-color:color-mix(in srgb,var(--c) 45%,transparent)}
a.qb i{flex:none;display:grid;place-items:center;width:38px;height:38px;border-radius:12px;font-style:normal;font-size:18px;background:color-mix(in srgb,var(--c) 16%,var(--card));transition:transform .3s}
a.qb:hover i{transform:rotate(-8deg) scale(1.12)}
a.qb>span{min-width:0}a.qb b{display:block;font-size:13px;line-height:1.2}a.qb small{display:block;font-size:11.5px;color:var(--muted);line-height:1.25}
/* top bar user chip */
.uchip{display:flex;align-items:center;gap:10px;padding:4px 12px 4px 4px;border:1px solid var(--line);border-radius:999px;background:var(--card);box-shadow:var(--sh)}
.uchip .av{display:grid;place-items:center;width:34px;height:34px;border-radius:50%;font-weight:800;font-size:14px;color:#fff;background:linear-gradient(135deg,#21bbb0,#f5ae77)}
.uchip .t{display:flex;flex-direction:column;line-height:1.15;min-width:0}.uchip b{font-size:13.5px;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.uchip small{font-size:11px;color:var(--muted)}
@media (max-width:1000px){.uchip .t{display:none}.uchip{padding:3px}}
/* ===== account menu, profile modals, sidebar promo ===== */
.sfoot{display:none}
.side{padding-bottom:14px}
.promo{position:relative;margin:auto 0 0;border-radius:20px;overflow:hidden;min-height:190px;background:linear-gradient(160deg,#5edbd3 0%,#2cdace 50%,#c86e29 100%);background-size:160% 160%;animation:thGrad 12s ease-in-out infinite alternate;box-shadow:0 14px 30px rgba(33,187,176,.35);flex:none}
.promo::after{content:"";position:absolute;top:0;left:-70%;width:40%;height:100%;background:linear-gradient(100deg,transparent,rgba(255,255,255,.28),transparent);transform:skewX(-18deg);animation:lgShine 5s 1.5s ease-in-out infinite;pointer-events:none}
.pscene{position:absolute;left:0;top:0;width:100%;height:150px;overflow:visible}
.pcap{animation:thBob 5.5s ease-in-out infinite;filter:drop-shadow(0 8px 6px rgba(12,51,48,.35))}
.ptas{transform-origin:158px 51px;animation:thSwing 2.8s ease-in-out infinite alternate}
.pc1{animation:lgDrift 14s linear infinite alternate}.pc2{animation:lgDrift 18s linear infinite alternate-reverse}.pc3{animation:lgDrift 20s linear infinite alternate}
.psp{transform-box:fill-box;transform-origin:center;animation:lgTw 3.2s ease-in-out infinite}.psp.s2{animation-delay:1.1s}.psp.s3{animation-delay:2s}
.promo::before{content:"";position:absolute;left:0;right:0;bottom:0;height:90px;background:linear-gradient(180deg,transparent,rgba(26,89,85,.6));z-index:1;pointer-events:none}
.ptx{position:relative;z-index:2;display:flex;flex-direction:column;gap:3px;padding:140px 16px 16px;color:#fff}
.ptx b{font-size:17px;font-weight:800;letter-spacing:-.3px;text-shadow:0 2px 10px rgba(15,69,66,.4)}
.ptx span{font-size:12.5px;opacity:.9;line-height:1.35}
@media (min-width:901px){.shell.col:not(.nosb) .promo{display:none}}
@media (max-height:760px){.promo{min-height:0}.ptx{padding-top:116px}.pscene{height:126px}}
@media (max-height:620px){.promo{display:none}}
.umenu{position:relative}
button.uchip{display:flex;align-items:center;gap:10px;padding:4px 12px 4px 4px;border:1px solid var(--line);border-radius:999px;background:var(--card);color:var(--text);box-shadow:var(--sh);font-weight:500;cursor:pointer}
button.uchip:hover:not(:disabled){filter:none;border-color:#75e8e0;box-shadow:var(--sh2)}
button.uchip .uc{font-size:11px;color:var(--muted);transition:transform .2s}
button.uchip[aria-expanded=true] .uc{transform:rotate(180deg)}
.av{background:var(--avc,linear-gradient(135deg,#21bbb0,#f5ae77))}
.udrop{position:absolute;right:0;top:calc(100% + 10px);z-index:90;width:272px;padding:8px;border-radius:18px;background:var(--card);border:1px solid var(--line);box-shadow:0 24px 60px rgba(21,70,67,.28);transform-origin:top right;animation:mvPop .2s cubic-bezier(.2,.9,.3,1)}
.udrop[hidden]{display:none}
.udrop::before{content:"";position:absolute;right:26px;top:-7px;width:12px;height:12px;background:var(--card);border-left:1px solid var(--line);border-top:1px solid var(--line);transform:rotate(45deg)}
.uhd{display:flex;align-items:center;gap:12px;padding:10px 10px 12px;margin-bottom:6px;border-bottom:1px solid var(--line)}
.uhd .av{display:grid;place-items:center;flex:none;width:44px;height:44px;border-radius:50%;font-size:18px;font-weight:800;color:#fff}
.uhd .t{display:flex;flex-direction:column;min-width:0;line-height:1.25}.uhd small{font-size:11px;color:var(--muted)}.uhd b{font-size:15px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.uhd em{font-style:normal;font-size:12px;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.udrop button[role=menuitem],.udrop a[role=menuitem]{display:flex;align-items:center;gap:12px;width:100%;padding:10px 12px;border:0;border-radius:12px;background:transparent;color:var(--text);font:inherit;font-size:14px;font-weight:500;text-align:left;text-decoration:none;cursor:pointer;box-shadow:none;transition:background .15s,transform .15s}
.udrop button[role=menuitem]:hover:not(:disabled),.udrop a[role=menuitem]:hover{background:color-mix(in srgb,var(--accent) 10%,transparent);filter:none;box-shadow:none;transform:translateX(3px)}
.udrop i{font-style:normal;width:22px;text-align:center;font-size:16px}
.udrop kbd{margin-left:auto;padding:2px 7px;border:1px solid var(--line);border-radius:7px;background:var(--bg);font-size:11px;color:var(--muted);font-family:inherit}
.udrop hr{border:0;border-top:1px solid var(--line);margin:6px 4px}
.udrop form{margin:0}.udrop .so{color:#e11d48!important}.udrop .so:hover{background:rgba(225,29,72,.1)!important}
.pfov{position:fixed;inset:0;z-index:300;display:grid;place-items:center;padding:16px;background:rgba(11,32,31,.5);backdrop-filter:blur(5px);animation:mvFade .18s}
.pfov[hidden]{display:none}
.pfb{width:min(460px,100%);max-height:calc(100vh - 32px);overflow:auto;padding:26px 26px 22px;border-radius:26px;background:var(--card);color:var(--text);box-shadow:0 40px 90px rgba(21,70,67,.4);animation:mvPop .28s cubic-bezier(.2,.9,.3,1)}
.pfb h2{margin:0 0 4px;font-size:21px;letter-spacing:-.4px}.pfb p.sub{margin:0 0 18px}
.pfp{display:flex;align-items:center;gap:14px;margin:0 0 16px;padding:14px;border-radius:18px;background:var(--bg)}
.pfp .av{display:grid;place-items:center;width:58px;height:58px;border-radius:50%;font-size:24px;font-weight:800;color:#fff;box-shadow:0 8px 18px rgba(33,187,176,.3);transition:background .25s}
.pfp div b{display:block;font-size:16px}.pfp div small{color:var(--muted)}
.av:has(>img),.who2 i:has(>img),button.ri .ic:has(>img){overflow:hidden;padding:0;background:var(--card)!important}
.av>img,.who2 i>img,button.ri .ic>img{display:block;width:100%;height:100%;object-fit:cover;border-radius:inherit}
.pfph{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}.pfph button{padding:8px 14px;font-size:13px}
.ust td .av2{display:inline-block;vertical-align:middle;width:22px;height:22px;border-radius:50%;object-fit:cover;margin-right:6px}
.pff{display:block;margin-top:12px;font-size:12.5px;font-weight:700;color:var(--muted)}
.pff input{display:block;width:100%;margin-top:5px;padding:12px 14px;font:inherit;font-size:15px;font-weight:400;color:var(--text);background:var(--bg);border:1.5px solid var(--line);border-radius:12px;outline:0;transition:border-color .2s,box-shadow .2s}
.pff input:focus{background:var(--card);border-color:#2cdace;box-shadow:0 0 0 4px rgba(44,218,206,.16)}
.pff input[readonly]{opacity:.7;cursor:not-allowed}
.sw{display:flex;gap:10px;flex-wrap:wrap;margin-top:8px}
.sw button{width:30px;height:30px;padding:0;border-radius:50%;border:3px solid var(--card);background:var(--c);box-shadow:0 0 0 2px transparent,0 3px 8px rgba(0,0,0,.18);transition:transform .15s,box-shadow .15s}
.sw button:hover:not(:disabled){transform:scale(1.15);filter:none}.sw button.on{box-shadow:0 0 0 2px var(--c),0 3px 8px rgba(0,0,0,.18);transform:scale(1.1)}
.pfm{min-height:20px;margin-top:12px;font-size:13.5px;font-weight:600}.pfm.err{color:#e11d48}.pfm.ok{color:#0b8a4f}
.pfa{display:flex;justify-content:flex-end;gap:10px;margin-top:10px}
.pfa button{padding:11px 20px}
.pst{height:6px;margin-top:8px;border-radius:4px;background:var(--bg);overflow:hidden}.pst i{display:block;height:100%;width:0;border-radius:4px;background:#e11d48;transition:width .25s,background .25s}
.pfh{margin-top:5px;font-size:12px;color:var(--muted)}
a.wapp{display:inline-flex;align-items:center;justify-content:center;flex:none;width:26px;height:26px;border-radius:50%;background:linear-gradient(145deg,#34d399,#16a34a);color:#fff;box-shadow:0 2px 6px rgba(22,163,74,.35);text-decoration:none;transition:transform .15s,box-shadow .15s}a.wapp:hover{transform:scale(1.14);box-shadow:0 4px 10px rgba(22,163,74,.5)}a.wapp svg{width:15px;height:15px;display:block}a.wapp svg .b{fill:#fff}a.wapp svg .h{fill:#16a34a}
.wapop{position:fixed;z-index:400;width:250px;padding:8px;border-radius:16px;background:var(--card);color:var(--text);border:1px solid var(--line);box-shadow:0 18px 50px rgba(17,57,55,.35);animation:mvFade .14s}
.wapop .wph{padding:8px 10px 10px}.wapop .wph b{display:block;font-size:14px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.wapop .wph small{display:block;margin-top:2px;color:var(--muted);font-size:11.5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.wapop button{display:flex;align-items:center;gap:10px;width:100%;padding:10px 12px;border:0;border-radius:11px;background:transparent;color:inherit;font:inherit;font-size:14px;font-weight:600;text-align:left;cursor:pointer}.wapop button:hover,.wapop button:focus-visible{background:var(--bg);outline:none}
.wapop button i{width:10px;height:10px;border-radius:50%;flex:none}
.wapop .wpf{padding:8px 10px 4px;color:var(--muted);font-size:11px;line-height:1.35}
#updated.warn{color:#b45309;font-weight:700}#updated.old{color:#e11d48;font-weight:800}
#svNav .svh{margin:10px 14px 2px;font-size:11px;font-weight:800;letter-spacing:.5px;text-transform:uppercase;color:var(--muted)}#svNav .svr{display:flex;align-items:center}#svNav .svr a{flex:1;min-width:0}#svNav .svr a .t{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}#svNav .svx{border:0;background:transparent;color:var(--muted);cursor:pointer;font-size:13px;padding:4px 8px;opacity:.6}#svNav .svx:hover{opacity:1;color:#e11d48}
#svBox{display:inline-flex;gap:6px;margin-left:8px}#svBox input{width:150px}
.hcheck{margin-top:8px;padding:10px 12px;border-radius:12px;background:var(--bg);font-size:13px}.hcheck.bad{color:#e11d48}.hcheck.good{color:#0b8a4f}
.usg{display:grid;grid-template-columns:repeat(auto-fit,minmax(120px,1fr));gap:10px;margin-top:6px}.usg div{padding:10px 12px;border-radius:14px;background:var(--bg)}.usg b{display:block;font-size:22px}.usg small{display:block;font-size:11.5px;color:var(--muted)}.usg em{font-style:normal;font-size:11px;color:var(--muted)}
.ush{margin:16px 0 6px;font-size:13px;font-weight:800}.ust{max-height:240px;overflow:auto;border-radius:12px;background:var(--bg)}.ust table{width:100%;border-collapse:collapse;font-size:12.5px}.ust th{text-align:left;color:var(--muted);font-weight:700;padding:7px 10px}.ust td{padding:6px 10px;border-top:1px solid color-mix(in srgb,var(--muted) 18%,transparent)}.ust tr.dead td{color:var(--muted)}
.usd{display:flex;align-items:flex-end;gap:5px;height:90px;padding:6px 8px;border-radius:12px;background:var(--bg)}.usd span{flex:1;height:100%;display:flex;flex-direction:column;justify-content:flex-end;align-items:center}.usd i{display:block;width:100%;min-height:2px;border-radius:4px 4px 0 0;background:linear-gradient(180deg,#75e8e0,#21bbb0)}.usd small{font-size:9.5px;color:var(--muted)}
@media (max-width:700px){.udrop{position:fixed;left:12px;right:12px;top:64px;width:auto}.udrop::before{display:none}}
/* ===== Universities / Reports pop-ups ===== */
#unav,#rnav,.usel{display:none!important}
.navh .chev{transform:none!important;font-size:18px;line-height:1}
.pkov{position:fixed;inset:0;z-index:320;display:grid;place-items:center;padding:20px;background:rgba(11,32,31,.5);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);animation:mvFade .18s}
.pkov[hidden]{display:none}
.gqov{position:fixed;inset:0;z-index:330;display:grid;place-items:center;padding:20px;background:rgba(11,32,31,.5);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);animation:mvFade .15s}
.gqov[hidden]{display:none}
.gqb{display:flex;flex-direction:column;width:min(680px,100%);height:min(560px,calc(100vh - 40px));border-radius:22px;background:var(--card);color:var(--text);border:1px solid var(--line);box-shadow:0 40px 100px rgba(21,70,67,.45);overflow:hidden;animation:mvPop .22s cubic-bezier(.2,.9,.3,1)}
.gqin{display:flex;align-items:center;gap:10px;padding:14px 18px;border-bottom:1px solid var(--line)}
.gqin svg{flex:none;color:var(--muted)}
.gqin input{flex:1;min-width:0;border:0!important;outline:0!important;box-shadow:none!important;background:transparent!important;color:var(--text);font:inherit;font-size:17px;padding:6px 0!important}
.gqin kbd{flex:none;padding:3px 8px;border:1px solid var(--line);border-radius:8px;background:var(--bg);color:var(--muted);font-size:11.5px;font-weight:700}
.gqres{flex:1;overflow:auto;padding:6px 8px 10px}
.gqh{padding:12px 12px 6px;font-size:11.5px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--muted)}
.gqi{display:flex;align-items:center;gap:12px;width:100%;text-align:left;padding:9px 12px;border:0;border-radius:12px;background:transparent;color:var(--text);font:inherit;cursor:pointer}
.gqi{box-shadow:none!important;transform:none!important;filter:none!important;margin:0}
.gqi.on,.gqi:hover{background:rgba(44,218,206,.12)!important}
.gqi .ic{flex:none;width:30px;height:30px;display:grid;place-items:center;border-radius:9px;background:var(--bg);font-size:15px}
.gqi span{flex:1;min-width:0;display:flex;flex-direction:column}
.gqi b{font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.gqi small{color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.gqi em{flex:none;font-style:normal;color:var(--muted);font-size:12px}
.gqi mark{background:rgba(44,218,206,.25);color:inherit;border-radius:3px;padding:0 1px}
.gqch{display:flex;flex-wrap:wrap;gap:8px;padding:2px 12px 8px}
.gqch .gqi{width:auto;padding:6px 12px;border:1px solid var(--line);border-radius:999px;font-size:13.5px}
.gqnone{padding:36px 16px;text-align:center;color:var(--muted)}
.gqall{display:block;margin:6px 4px 0;padding:10px 12px;border-radius:12px;text-align:center;font-weight:700;color:#2cdace;text-decoration:none;background:rgba(44,218,206,.08)}
.gqft{padding:8px 16px;border-top:1px solid var(--line);font-size:12px;color:var(--muted)}
.pkb{display:flex;flex-direction:column;width:min(900px,100%);max-height:calc(100vh - 40px);border-radius:28px;background:var(--card);color:var(--text);box-shadow:0 40px 100px rgba(21,70,67,.45);overflow:hidden;animation:mvPop .3s cubic-bezier(.2,.9,.3,1)}
.pkh{position:relative;padding:24px 28px 18px;background:radial-gradient(circle at 92% 0%,rgba(255,255,255,.25),transparent 42%),linear-gradient(120deg,#21bbb0,#2cdace 55%,#c86e29);color:#fff}
.pkh h2{margin:0 0 3px;font-size:23px;letter-spacing:-.5px;display:flex;align-items:center;gap:10px}
.pkh p{margin:0;font-size:13.5px;opacity:.9}
.pkx{position:absolute;right:16px;top:16px;width:36px;height:36px;padding:0;display:grid;place-items:center;border-radius:12px;border:0;background:rgba(255,255,255,.2);color:#fff;font-size:18px;box-shadow:none}
.pkx:hover:not(:disabled){background:rgba(255,255,255,.34);filter:none;box-shadow:none}
.pkt{display:flex;gap:10px;flex-wrap:wrap;padding:14px 24px 4px}
.pkt input{flex:1 1 220px;min-width:0;padding:12px 16px;font:inherit;font-size:15px;color:var(--text);background:var(--bg);border:1.5px solid var(--line);border-radius:14px;outline:0}
.pkt input:focus{background:var(--card);border-color:#2cdace;box-shadow:0 0 0 4px rgba(44,218,206,.16)}
.pkt select{padding:12px 14px;font:inherit;border-radius:14px;background:var(--bg);border:1.5px solid var(--line);color:var(--text)}
.pkg{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:14px;padding:16px 24px 20px;overflow:auto}
.pkc{position:relative;border-radius:18px;background:var(--bg);border:1.5px solid transparent;transition:transform .2s,box-shadow .2s,border-color .2s,background .2s;animation:mvIn .35s both;animation-delay:calc(var(--i,0)*.025s)}
.pkc:hover{transform:translateY(-3px);box-shadow:var(--sh2);border-color:color-mix(in srgb,var(--c,#21bbb0) 55%,transparent);background:var(--card)}
.pkc.on{border-color:#2cdace;background:color-mix(in srgb,#2cdace 8%,var(--card))}
.pkc>a{height:100%;box-sizing:border-box;display:grid;grid-template-columns:44px minmax(0,1fr) auto;grid-template-rows:auto auto;align-items:center;gap:6px 12px;padding:14px 38px 14px 14px;color:var(--text);text-decoration:none}
.pkc .av{grid-row:span 1;display:grid;place-items:center;width:44px;height:44px;border-radius:14px;font-weight:800;font-size:14px;color:var(--c);background:color-mix(in srgb,var(--c) 18%,transparent);border:1px solid color-mix(in srgb,var(--c) 40%,transparent)}
.pkc .tx{min-width:0}.pkc .tx b{display:block;font-size:14.5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.pkc .tx small{display:block;color:var(--muted);font-size:12px;margin-top:1px}
.pkc .od{color:#e11d48;font-weight:700}
.pkc .ct{text-align:right;line-height:1.1}.pkc .ct b{font-size:19px;font-variant-numeric:tabular-nums;letter-spacing:-.4px}.pkc .ct small{display:block;color:var(--muted);font-size:11px}
.pkc .bar{grid-column:1/-1;height:6px;border-radius:5px;background:color-mix(in srgb,var(--line) 70%,transparent);overflow:hidden}
.pkc .bar i{display:block;height:100%;border-radius:5px;background:linear-gradient(90deg,var(--c),color-mix(in srgb,var(--c) 50%,#fff));transform-origin:left;animation:mvGrow .8s cubic-bezier(.2,.7,.2,1) both .15s}
.pkck{position:absolute;right:12px;top:12px;width:18px;height:18px;margin:0;accent-color:#21bbb0;cursor:pointer;opacity:.45;transition:opacity .15s}
.pkc:hover .pkck,.pkck:checked{opacity:1}
.pkc.all>a{grid-template-columns:44px minmax(0,1fr) auto;background:linear-gradient(120deg,color-mix(in srgb,#21bbb0 14%,transparent),color-mix(in srgb,#ea8b42 10%,transparent));border-radius:18px}
.pkf{display:flex;align-items:center;gap:10px;padding:14px 24px;border-top:1px solid var(--line);background:var(--card)}
.pkf .sp{flex:1;color:var(--muted);font-size:13px}
.pkf button{padding:11px 20px}
.pkf[hidden]{display:none}
.pkn{grid-column:1/-1;padding:30px;text-align:center;color:var(--muted)}
.pkr>a{grid-template-columns:48px minmax(0,1fr)!important;align-items:start!important;padding-right:14px!important}
.pkr .ri{display:grid;place-items:center;width:48px;height:48px;border-radius:15px;font-size:22px;background:color-mix(in srgb,var(--c) 16%,transparent)}
.pkr .tx b{white-space:normal}.pkr .tx small{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;line-height:1.4}
.pkr .go{grid-column:2;color:var(--accent);font-weight:700;font-size:12.5px}
.navh .prl .bd{margin-left:10px;vertical-align:middle;letter-spacing:0;text-transform:none}.navh .prl .bd:empty{display:none}.navh.on{background:#dffbf9;color:#28958e}.shell.col #navP{position:relative}
.pkg.prio{grid-template-columns:repeat(auto-fit,minmax(260px,1fr));padding:20px 24px 24px}
.pkb:has(.pkg.prio){width:min(720px,100%)}
.prc>a{grid-template-columns:52px minmax(0,1fr) auto!important;grid-template-rows:auto!important;padding:18px 16px!important;border-radius:18px}
.prc .ri{display:grid;place-items:center;width:52px;height:52px;border-radius:16px;font-size:24px;background:color-mix(in srgb,var(--c) 16%,transparent);border:1px solid color-mix(in srgb,var(--c) 35%,transparent)}
.prc .tx b{white-space:normal;font-size:15px}.prc .ct{min-width:58px}.prc .ct b{display:block;font-size:26px;color:var(--c)}
.pkov{--s1:#2a78d6;--s2:#eb6834;--s3:#1baf7a;--s4:#eda100;--s5:#e87ba4;--s6:#0a9a4a;--s7:#30a49d}
@media (prefers-color-scheme:dark){.pkov{--s1:#3987e5;--s2:#d95926;--s3:#199e70;--s4:#c98500;--s5:#d55181;--s6:#17a35a;--s7:#63d1c9}}
#btnRefresh{display:none!important}
/* list layout */
.lst .pkg{display:block;padding:6px 12px 12px}
.lst .pkc{display:block;border-radius:12px;margin:0;border:0;border-bottom:1px solid var(--line);background:transparent;box-shadow:none;animation:mvFade .25s both;animation-delay:calc(var(--i,0)*.012s)}
.lst .pkc:last-child{border-bottom:0}
.lst .pkc:hover{transform:none;box-shadow:none;border-color:transparent;background:color-mix(in srgb,#2cdace 9%,transparent)}
.lst .pkc:hover+.pkc,.lst .pkc:hover{border-bottom-color:transparent}
.lst .pkc.on{background:color-mix(in srgb,#2cdace 13%,transparent);border-bottom-color:transparent;box-shadow:inset 3px 0 0 #2cdace}
.lst .pkc>a{grid-template-columns:36px minmax(0,1fr) 110px 62px;grid-template-rows:auto;gap:12px;padding:10px 12px 10px 46px;border-radius:12px}
.lst .pkc .av{width:36px;height:36px;border-radius:50%;font-size:12px}
.lst .pkc .tx b{font-size:14.5px}.lst .pkc .tx small{margin-top:0}
.lst .pkc .ct{grid-column:4;display:flex;align-items:baseline;justify-content:flex-end;gap:5px}.pkc .ct b{font-size:16px}.pkc .ct small{display:inline}
.lst .pkc .bar{grid-column:3;grid-row:1;height:6px}
.lst .pkck{left:15px;right:auto;top:50%;margin-top:-9px;opacity:.55}
.lst .pkc.all>a{grid-template-columns:36px minmax(0,1fr) 62px;padding-left:46px;background:linear-gradient(90deg,color-mix(in srgb,#21bbb0 14%,transparent),transparent)}
.lst .pkc.all .av{border-radius:50%}
.lst .pkc.all .ct{grid-column:3}
.lst .pkg .pkc.all{margin-bottom:4px;border-bottom:0}
@media (max-width:700px){.lst .pkc>a{grid-template-columns:34px minmax(0,1fr) 54px;padding-left:44px}.lst .pkc .bar{display:none}.lst .pkc .ct{grid-column:3}}
</style>
<?php $welcome = !empty($_SESSION['welcome']); unset($_SESSION['welcome']); ?>
<?php if ($welcome) { ?>
<style>#ppwel{position:fixed;inset:0;z-index:10000;background:radial-gradient(120% 90% at 50% 36%,#19cec2 0,#23746f 38%,#113f3c 72%,#092220 100%);animation:ppwFail .5s 9s forwards}@keyframes ppwFail{to{opacity:0;visibility:hidden}}</style>
<div id="ppwel" aria-hidden="true"></div>
<?php } ?>
<script>window.__ppWelcome = <?= $welcome ? 'true' : 'false' ?>;</script>
<div class="shell" id="shell">
<div class="sidesp" aria-hidden="true"></div>
<aside class="side" id="side">
  <a class="brand" href="#/"><span class="logo" id="logoBtn" title="Show / hide the side menu" role="button" tabindex="0" aria-label="Show or hide the side menu"><span class="lg-mark sbm" aria-hidden="true"><i></i><i></i><i></i><i></i></span></span><span class="t">Pranav Pipeline</span></a>
  <nav class="nav" id="nav">
    <a data-nav="home" href="#/" title="Dashboard"><i>🏠</i><span class="t">Dashboard</span></a>
    <a data-nav="master" href="#/master" title="Master overview"><i>📊</i><span class="t">Master overview</span></a>
    <a data-nav="all" href="#/master/sheet" title="All deals"><i>📋</i><span class="t">All deals</span></a>
    <div id="svNav"></div>
  </nav>
  <button type="button" class="navh" id="navP" aria-expanded="false" aria-haspopup="dialog"><span class="prl">🔥<span class="t"> Priority leads</span><span class="bd" id="navPrio"></span></span><span class="chev">›</span></button>
  <button type="button" class="navh" id="navU" aria-expanded="false"><span>🎓<span class="t"> Universities</span></span><span class="chev">›</span></button>
  <div class="unav" id="unav" hidden></div>
  <button type="button" class="navh" id="navR" aria-expanded="false"><span>📑<span class="t"> Reports</span></span><span class="chev">›</span></button>
  <div class="unav rnav" id="rnav" hidden></div>
  <div class="promo" aria-hidden="true">
    <svg class="pscene" viewBox="0 0 220 150" preserveAspectRatio="xMidYMid slice">
      <defs>
        <linearGradient id="pgB" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2fd2c7"/><stop offset="1" stop-color="#20524f"/></linearGradient>
        <linearGradient id="pgC" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#35807b"/><stop offset="1" stop-color="#1a4542"/></linearGradient>
        <linearGradient id="pgG" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fde68a"/><stop offset="1" stop-color="#f59e0b"/></linearGradient>
        <symbol id="pcl" viewBox="0 0 200 80"><path d="M20 80a20 20 0 0 1-2-39.9A32 32 0 0 1 78 28a38 38 0 0 1 72 6 26 26 0 0 1 32 26 20 20 0 0 1-8 20z"/></symbol>
      </defs>
      <g class="pc pc1"><use href="#pcl" x="-20" y="46" width="120" height="48" fill="#fff" opacity=".35"/></g>
      <g class="pc pc2"><use href="#pcl" x="120" y="30" width="100" height="40" fill="#fff" opacity=".28"/></g>
      <g class="pcap">
        <path d="M110 20 172 48 110 76 48 48z" fill="url(#pgB)" stroke="#fff" stroke-opacity=".45" stroke-width="1.4" stroke-linejoin="round"/>
        <path d="M110 22 168 48 110 52 52 48z" fill="#fff" opacity=".14"/>
        <path d="M78 58v18c0 7 15 14 32 14s32-7 32-14V58l-32 16z" fill="url(#pgC)" stroke="#fff" stroke-opacity=".3" stroke-width="1.2"/>
        <g class="ptas"><path d="M158 51v28" stroke="url(#pgG)" stroke-width="3" stroke-linecap="round"/><circle cx="158" cy="83" r="4.5" fill="url(#pgG)"/><path d="M155.5 86l-2 8M158 87v9M160.5 86l2 8" stroke="#f59e0b" stroke-width="1.6" stroke-linecap="round"/></g>
      </g>
      <g class="pc pc3"><use href="#pcl" x="30" y="96" width="170" height="68" fill="#fff" opacity=".5"/></g>
      <path class="psp s1" d="M30 30l2.4 6.4 6.4 2.4-6.4 2.4L30 47.6l-2.4-6.4L21 38.8l6.6-2.4z" fill="#fff"/>
      <path class="psp s2" d="M196 24l1.8 4.6 4.6 1.8-4.6 1.8L196 37l-1.8-4.8-4.6-1.8 4.6-1.8z" fill="#fff"/>
      <path class="psp s3" d="M188 78l1.5 3.8 3.8 1.5-3.8 1.5L188 88.6l-1.5-3.8-3.8-1.5 3.8-1.5z" fill="#fde68a"/>
    </svg>
    <div class="ptx"><b>Track. Manage. Grow.</b><span>All your education deals in one place.</span></div>
  </div>
</aside>
<div class="sbback" id="sbback"></div>
<div class="pkov" id="pkOv" hidden><div class="pkb" id="pkBox" role="dialog" aria-modal="true"></div></div><div class="gqov" id="gqOv" hidden><div class="gqb" role="dialog" aria-modal="true" aria-label="Search"><div class="gqin"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg><input id="gqIn" type="text" placeholder="Search a student, phone, email, university, report…" autocomplete="off" spellcheck="false"><kbd>Esc</kbd></div><div class="gqres" id="gqRes"></div><div class="gqft">↑ ↓ to move · Enter to open · Shift+Enter to see all in the table · Esc to close</div></div></div><div class="pfov" id="pfOv" hidden><div class="pfb" role="dialog" aria-modal="true" id="pfBox"></div></div>
<div class="main">
  <div class="top">
    <button class="ghost menu" id="btnMenu" type="button" aria-label="Show or hide the side menu" title="Show / hide the side menu">☰</button>
    <div class="gs">
      <input id="gsearch" type="search" placeholder="Search a student, phone, email or university…" autocomplete="off"><kbd class="kbd">Ctrl K</kbd>
      <div id="gsres" class="gsres" hidden></div>
    </div>
    <div class="tr"><span class="sub" id="updated">Loading…</span><button type="button" class="thm" id="btnTheme" aria-label="Switch theme" title="Switch theme"><svg class="ico-sun" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg><svg class="ico-moon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg></button><button id="btnRefresh" type="button">↻ Refresh data</button><div class="umenu" id="umenu"><button type="button" class="uchip" id="uBtn" aria-haspopup="true" aria-expanded="false" title="Account menu"><span class="av" id="uAv"></span><span class="t"><b id="uNm"></b><small id="uSc"></small></span><span class="uc">▾</span></button>
      <div class="udrop" id="uDrop" role="menu" hidden>
        <div class="uhd"><span class="av" id="uAv2"></span><span class="t"><small>Signed in as</small><b id="uNm2"></b><em id="uUn2"></em></span></div>
        <button type="button" role="menuitem" data-a="profile"><i>👤</i>Your profile</button>
        <button type="button" role="menuitem" data-a="pwd"><i>🔑</i>Change password</button>
        <button type="button" role="menuitem" data-a="sound"><i>🔊</i><span id="soundMode">Sound &amp; voice: on 🔊</span></button>
        <button type="button" role="menuitem" data-a="voice"><i>🗣️</i><span id="voiceName">Voice: default</span></button>
        <button type="button" role="menuitem" data-a="wake" id="wakeItem" hidden><i>🎙️</i><span id="wakeMode">Say “Hey Pranav”: off</span></button>
        <button type="button" role="menuitem" data-a="monkey"><i>🐒</i><span id="monkeyMode">Pranav: playing (tap to rest)</span></button>
        <?php if (user_scope() === null) { ?><button type="button" role="menuitem" data-a="usage"><i>📊</i>Usage (who uses what)</button><?php } ?>
        <button type="button" role="menuitem" data-a="refresh"><i>↻</i>Refresh data</button>
        <hr>
        <form method="post" id="lgoForm"><input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>"><input type="hidden" name="logout" value="1"><button type="button" id="lgoBtn" role="menuitem" class="so"><i>🚪</i>Log out</button></form>
      </div></div></div>
  </div>
<div class="rfbar" id="rfbar" hidden><i></i></div>
<div class="gfbar" id="gfbar" hidden></div>
<div class="wrap">
  <div id="banner" class="banner" hidden></div>
  <section id="home" hidden>
    <div class="hero" id="hero">
      <div class="ht"><div class="hello" id="heroHi">Hello,</div><h1><span id="heroName">there</span> <span class="wave">👋</span></h1><p>Here’s your pipeline overview.</p><p id="masterNum" style="margin-top:6px;font-size:13.5px;opacity:.7"></p><a class="hbtn" href="#/master">Open master overview →</a></div>
      <div class="hscene" id="hscene" aria-hidden="true">
        <svg width="0" height="0" style="position:absolute"><defs><symbol id="thcl" viewBox="0 0 200 80"><rect x="6" y="38" width="188" height="40" rx="20"/><circle cx="62" cy="40" r="28"/><circle cx="108" cy="32" r="32"/><circle cx="154" cy="46" r="22"/></symbol></defs></svg>
        <svg class="cl c1" viewBox="0 0 200 80"><use href="#thcl"/></svg><svg class="cl c2" viewBox="0 0 200 80"><use href="#thcl"/></svg><svg class="cl c3" viewBox="0 0 200 80"><use href="#thcl"/></svg>
        <span class="spk" style="left:12%;top:58%;animation-delay:.2s"></span><span class="spk" style="left:70%;top:20%;animation-delay:1.2s;width:10px;height:10px"></span><span class="spk" style="left:46%;top:12%;animation-delay:2.1s;width:11px;height:11px;background:#f5ae77"></span>
        <div class="bld"><svg viewBox="228 0 344 340" aria-hidden="true"><defs><linearGradient id="cwW" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#fbf6ea"/><stop offset="1" stop-color="#e2d9c5"/></linearGradient><linearGradient id="cwWs" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#bdb5a2"/><stop offset="1" stop-color="#d4ccb8"/></linearGradient><linearGradient id="cwC" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#cfc7b3"/><stop offset=".28" stop-color="#fffdf7"/><stop offset=".62" stop-color="#eee8da"/><stop offset="1" stop-color="#aaa28e"/></linearGradient><linearGradient id="cwR" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#37c9ba"/><stop offset="1" stop-color="#14796f"/></linearGradient><linearGradient id="cwGl" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#fff0b8"/><stop offset="1" stop-color="#ffb23e"/></linearGradient><linearGradient id="cwGc" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#a6e9e3"/><stop offset="1" stop-color="#3f9c98"/></linearGradient><linearGradient id="cwD" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#b8821f"/><stop offset=".35" stop-color="#ffe58f"/><stop offset=".7" stop-color="#e0a62a"/><stop offset="1" stop-color="#8f5f12"/></linearGradient><linearGradient id="cwS" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#f7f0de"/><stop offset="1" stop-color="#cdbf9f"/></linearGradient><linearGradient id="cwH" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#3a9b58"/><stop offset="1" stop-color="#14532d"/></linearGradient><linearGradient id="cwP" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#f2d9a6"/><stop offset="1" stop-color="#d6b274"/></linearGradient><radialGradient id="cwT" cx=".4" cy=".35" r=".75"><stop offset="0" stop-color="#2f9e55"/><stop offset="1" stop-color="#0f4a27"/></radialGradient><radialGradient id="cwLg"><stop offset="0" stop-color="#ffe9a0" stop-opacity=".85"/><stop offset="1" stop-color="#ffe9a0" stop-opacity="0"/></radialGradient><radialGradient id="cwDoor" cx=".5" cy=".8" r=".9"><stop offset="0" stop-color="#ffe9a8"/><stop offset="1" stop-color="#e58b1f"/></radialGradient><linearGradient id="cwSh" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#000" stop-opacity=".0"/><stop offset="1" stop-color="#000" stop-opacity=".35"/></linearGradient></defs><rect x="364" y="84" width="72" height="62" fill="url(#cwW)"/><rect x="358" y="80" width="84" height="7" rx="2" fill="#d9d0ba"/><path d="M374 122V102.0a6.0 6.0 0 0 1 12 0V122Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M380.0 97V122M374 110.3H386" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="372" y="122" width="16" height="3.5" rx="1" fill="#d8cfb9"/><path d="M394 122V102.0a6.0 6.0 0 0 1 12 0V122Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M400.0 97V122M394 110.3H406" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="392" y="122" width="16" height="3.5" rx="1" fill="#d8cfb9"/><path d="M414 122V102.0a6.0 6.0 0 0 1 12 0V122Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M420.0 97V122M414 110.3H426" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="412" y="122" width="16" height="3.5" rx="1" fill="#d8cfb9"/><path d="M356 82Q356 20 400 14Q444 20 444 82Z" fill="url(#cwD)"/><path d="M400 14Q384 40 380 82M400 14Q416 40 420 82M400 14V82" stroke="#fff3c2" stroke-width="1.6" opacity=".55" fill="none"/><rect x="399" y="0" width="3" height="16" fill="#c99a2a"/><path class="flag" d="M402 2h22l-7 6 7 6h-22z" fill="#ef4444"/><path d="M246 180L400 114L554 180Z" fill="url(#cwW)"/><path d="M268 176L400 124L532 176Z" fill="#e9e2d0"/><path d="M246 180L400 114L554 180" fill="none" stroke="url(#cwR)" stroke-width="7" stroke-linejoin="round"/><circle cx="400" cy="156" r="13" fill="#fff" stroke="#17b3a6" stroke-width="4"/><path d="M400 148v8l6 4" stroke="#2a3b4a" stroke-width="2" fill="none" stroke-linecap="round"/><rect x="250" y="178" width="300" height="8" fill="#d9d0ba"/><rect x="256" y="186" width="288" height="12" fill="url(#cwW)"/><rect x="256" y="196" width="288" height="3" fill="#b9b09a"/><rect x="262" y="187" width="5" height="8" fill="#cfc6af"/><rect x="274" y="187" width="5" height="8" fill="#cfc6af"/><rect x="286" y="187" width="5" height="8" fill="#cfc6af"/><rect x="298" y="187" width="5" height="8" fill="#cfc6af"/><rect x="310" y="187" width="5" height="8" fill="#cfc6af"/><rect x="322" y="187" width="5" height="8" fill="#cfc6af"/><rect x="334" y="187" width="5" height="8" fill="#cfc6af"/><rect x="346" y="187" width="5" height="8" fill="#cfc6af"/><rect x="358" y="187" width="5" height="8" fill="#cfc6af"/><rect x="370" y="187" width="5" height="8" fill="#cfc6af"/><rect x="382" y="187" width="5" height="8" fill="#cfc6af"/><rect x="394" y="187" width="5" height="8" fill="#cfc6af"/><rect x="406" y="187" width="5" height="8" fill="#cfc6af"/><rect x="418" y="187" width="5" height="8" fill="#cfc6af"/><rect x="430" y="187" width="5" height="8" fill="#cfc6af"/><rect x="442" y="187" width="5" height="8" fill="#cfc6af"/><rect x="454" y="187" width="5" height="8" fill="#cfc6af"/><rect x="466" y="187" width="5" height="8" fill="#cfc6af"/><rect x="478" y="187" width="5" height="8" fill="#cfc6af"/><rect x="490" y="187" width="5" height="8" fill="#cfc6af"/><rect x="502" y="187" width="5" height="8" fill="#cfc6af"/><rect x="514" y="187" width="5" height="8" fill="#cfc6af"/><rect x="526" y="187" width="5" height="8" fill="#cfc6af"/><rect x="538" y="187" width="5" height="8" fill="#cfc6af"/><rect x="262" y="199" width="276" height="113" fill="url(#cwWs)"/><path d="M378 312V258a22 22 0 0 1 44 0V312Z" fill="url(#cwDoor)" stroke="#6d5a3a" stroke-width="3"/><path d="M400 236V312M378 270H422" stroke="#6d5a3a" stroke-width="1.5" opacity=".7"/><path d="M280 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M287.0 223V256M280 240.7H294" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="278" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><path d="M312 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M319.0 223V256M312 240.7H326" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="310" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><path d="M344 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M351.0 223V256M344 240.7H358" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="342" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><path d="M440 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M447.0 223V256M440 240.7H454" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="438" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><path d="M472 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M479.0 223V256M472 240.7H486" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="470" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><path d="M504 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M511.0 223V256M504 240.7H518" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="502" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><rect x="259.0" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="262.0" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="259.0" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M267.0 208V302M273.0 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="295.3" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="298.3" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="295.3" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M303.3 208V302M309.3 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="331.6" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="334.6" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="331.6" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M339.6 208V302M345.6 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="367.9" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="370.9" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="367.9" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M375.9 208V302M381.9 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="404.2" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="407.2" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="404.2" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M412.2 208V302M418.2 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="440.5" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="443.5" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="440.5" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M448.5 208V302M454.5 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="476.79999999999995" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="479.79999999999995" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="476.79999999999995" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M484.79999999999995 208V302M490.79999999999995 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="513.0999999999999" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="516.0999999999999" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="513.0999999999999" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M521.0999999999999 208V302M527.0999999999999 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="258" y="312" width="284" height="7" fill="url(#cwS)"/><rect x="246" y="319" width="308" height="7" fill="url(#cwS)"/><rect x="234" y="326" width="332" height="8" fill="url(#cwS)"/><rect x="234" y="334" width="332" height="3" fill="#000" opacity=".18"/><path d="M334 337h132l60 63H274z" fill="url(#cwP)"/><g><rect x="40" y="206" width="224" height="106" fill="url(#cwW)"/><rect x="40" y="256" width="224" height="3" fill="#cdc4ad"/><rect x="40" y="203" width="224" height="3" fill="#d9d0ba"/><path d="M32 206h240l-12-20H44z" fill="url(#cwR)"/><path d="M44 186h216" stroke="#7ff0e2" stroke-width="1.6" opacity=".6"/><path d="M32 206h240" stroke="#0f5e57" stroke-width="2.5"/><path d="M62 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M71.0 215V246M62 231.6H80" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="60" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M62 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M71.0 269V298M62 284.5H80" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="60" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M100 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M109.0 215V246M100 231.6H118" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="98" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M100 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M109.0 269V298M100 284.5H118" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="98" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M138 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M147.0 215V246M138 231.6H156" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="136" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M138 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M147.0 269V298M138 284.5H156" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="136" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M176 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M185.0 215V246M176 231.6H194" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="174" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M176 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M185.0 269V298M176 284.5H194" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="174" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M214 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M223.0 215V246M214 231.6H232" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="212" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M214 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M223.0 269V298M214 284.5H232" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="212" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><rect x="40" y="206" width="18" height="106" fill="url(#cwW)" opacity=".9"/><path d="M58 206V312" stroke="#b9b09a" stroke-width="1.5"/><rect x="40" y="210" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="225" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="240" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="255" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="270" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="285" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="300" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="206" width="18" height="106" fill="url(#cwW)" opacity=".9"/><path d="M264 206V312" stroke="#b9b09a" stroke-width="1.5"/><rect x="246" y="210" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="225" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="240" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="255" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="270" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="285" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="300" width="18" height="1.4" fill="#c7bea8"/><rect x="46" y="300" width="206" height="14" rx="7" fill="url(#cwH)"/><circle cx="54.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="68.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="83.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="97.5" cy="306" r="2.1" fill="#ff6b8a"/><circle cx="112.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="126.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="141.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="155.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="170.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="184.5" cy="306" r="2.1" fill="#ff6b8a"/><circle cx="199.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="213.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="228.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="242.5" cy="306" r="2.1" fill="#ffb347"/></g><g transform="translate(800 0) scale(-1 1)"><rect x="40" y="206" width="224" height="106" fill="url(#cwW)"/><rect x="40" y="256" width="224" height="3" fill="#cdc4ad"/><rect x="40" y="203" width="224" height="3" fill="#d9d0ba"/><path d="M32 206h240l-12-20H44z" fill="url(#cwR)"/><path d="M44 186h216" stroke="#7ff0e2" stroke-width="1.6" opacity=".6"/><path d="M32 206h240" stroke="#0f5e57" stroke-width="2.5"/><path d="M62 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M71.0 215V246M62 231.6H80" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="60" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M62 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M71.0 269V298M62 284.5H80" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="60" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M100 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M109.0 215V246M100 231.6H118" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="98" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M100 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M109.0 269V298M100 284.5H118" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="98" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M138 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M147.0 215V246M138 231.6H156" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="136" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M138 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M147.0 269V298M138 284.5H156" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="136" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M176 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M185.0 215V246M176 231.6H194" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="174" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M176 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M185.0 269V298M176 284.5H194" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="174" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M214 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M223.0 215V246M214 231.6H232" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="212" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M214 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M223.0 269V298M214 284.5H232" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="212" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><rect x="40" y="206" width="18" height="106" fill="url(#cwW)" opacity=".9"/><path d="M58 206V312" stroke="#b9b09a" stroke-width="1.5"/><rect x="40" y="210" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="225" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="240" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="255" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="270" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="285" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="300" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="206" width="18" height="106" fill="url(#cwW)" opacity=".9"/><path d="M264 206V312" stroke="#b9b09a" stroke-width="1.5"/><rect x="246" y="210" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="225" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="240" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="255" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="270" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="285" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="300" width="18" height="1.4" fill="#c7bea8"/><rect x="46" y="300" width="206" height="14" rx="7" fill="url(#cwH)"/><circle cx="54.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="68.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="83.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="97.5" cy="306" r="2.1" fill="#ff6b8a"/><circle cx="112.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="126.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="141.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="155.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="170.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="184.5" cy="306" r="2.1" fill="#ff6b8a"/><circle cx="199.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="213.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="228.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="242.5" cy="306" r="2.1" fill="#ffb347"/></g><circle cx="222" cy="288" r="30" fill="url(#cwLg)"/><rect x="220.5" y="292" width="3" height="42" fill="#2a3b4a"/><rect x="217" y="282" width="10" height="12" rx="3" fill="#ffe08a" stroke="#2a3b4a" stroke-width="1.5"/><path d="M215 282h14l-7-7z" fill="#2a3b4a"/><circle cx="578" cy="288" r="30" fill="url(#cwLg)"/><rect x="576.5" y="292" width="3" height="42" fill="#2a3b4a"/><rect x="573" y="282" width="10" height="12" rx="3" fill="#ffe08a" stroke="#2a3b4a" stroke-width="1.5"/><path d="M571 282h14l-7-7z" fill="#2a3b4a"/></svg></div>
        <svg class="trees" viewBox="0 0 330 70"><g fill="#fff" opacity=".4"><circle cx="14" cy="40" r="14"/><circle cx="30" cy="34" r="17"/><circle cx="46" cy="42" r="13"/><circle cx="286" cy="38" r="15"/><circle cx="304" cy="32" r="17"/><circle cx="320" cy="42" r="12"/></g></svg>
        
        <span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span>
        <span class="spk" style="left:6%;top:30%;animation-delay:.7s;width:10px;height:10px"></span><span class="spk" style="left:88%;top:62%;animation-delay:1.6s;width:12px;height:12px;background:#fff"></span><span class="spk" style="left:58%;top:78%;animation-delay:2.4s;width:9px;height:9px"></span>
        <div class="stack"><svg viewBox="0 0 270 230">
          <g class="bk bk1"><rect x="8" y="170" width="250" height="40" rx="8" fill="#31837e"/><rect x="22" y="177" width="232" height="26" rx="4" fill="#f4fefd"/><path d="M30 190h210" stroke="#d7f6f4" stroke-width="2"/></g>
          <g class="bk bk2"><rect x="22" y="132" width="232" height="38" rx="8" fill="#ec4899"/><rect x="34" y="139" width="216" height="24" rx="4" fill="#fff5fa"/><path d="M42 151h196" stroke="#fbcfe3" stroke-width="2"/></g>
          <g class="bk bk3"><rect x="12" y="96" width="240" height="36" rx="8" fill="#21bbb0"/><rect x="26" y="103" width="222" height="22" rx="4" fill="#f4fefd"/><path d="M34 114h204" stroke="#d7f6f4" stroke-width="2"/></g>
          <g class="cap"><path d="M132 14 216 52 132 90 48 52z" fill="#173230"/><path d="M82 70v22c0 10 22 20 50 20s50-10 50-20V70l-50 22z" fill="#234c49"/><g class="tas"><path d="M206 56v36" stroke="#fbbf24" stroke-width="4" stroke-linecap="round"/><circle cx="206" cy="97" r="6" fill="#fbbf24"/></g></g>
        </svg></div>
      </div>
      <div class="hs" id="heroStats"></div>
    </div>
    <div id="hKpis" class="hk"></div>
    <div id="hKpis2" class="hk"></div>
    <div class="hgrid">
      <div class="chartcard g-trend"><div class="chh"><h3>Deals trend</h3><select id="selMonths"><option value="6">Last 6 months</option><option value="12">Last 12 months</option></select></div><div id="chTrend"></div></div>
      <div class="chartcard g-status"><div class="chh"><h3>Deal status</h3></div><div id="chStatus"></div></div>
      <div class="chartcard g-act"><div class="chh"><h3>Recent activity</h3><a class="vall" href="#/reports/activity">View all →</a></div><div id="chAct"><div class="empty">Loading…</div></div></div>
      <div class="chartcard g-prog"><div class="chh"><h3>Deals by program</h3></div><div id="chProg"></div></div>
      <div class="chartcard g-top"><div class="chh"><h3>Top universities</h3><select id="selTop"><option value="5">Top 5</option><option value="10">Top 10</option><option value="0">All</option></select></div><div id="chTop"></div></div>
      <div class="chartcard g-quick"><div class="chh"><h3>Quick actions</h3></div><div id="chQuick" class="qa"></div></div>
    </div>
    <div class="unihead">
      <div class="uleft"><h2>Universities</h2><span class="seg2"><button type="button" id="vGrid" class="on" aria-label="Grid">▦</button><button type="button" id="vList" aria-label="List">☰</button></span></div>
      <div class="uctl">
        <input id="uq" type="search" placeholder="Search universities…" autocomplete="off">
        <select id="uSort"><option value="deals">Sort: Deals (high to low)</option><option value="dealsAsc">Sort: Deals (low to high)</option><option value="name">Sort: A to Z</option><option value="stale">Sort: Most follow-ups overdue</option></select>
      </div>
    </div>
    <div id="uniGrid" class="ugrid"></div>
    <div id="homeEmpty" class="empty" hidden></div>
  </section>
  <section id="overview" hidden>
    <div class="sheethead ov"><div><a class="back" href="#/">← All universities</a><h2 class="dashtitle" id="ovTitle"></h2></div><a class="btnlink" id="ovAll" href="#/master/sheet"></a></div>
    <div id="kpis" class="kpis"></div>
    <div id="matrix" class="matrixwrap" hidden></div>
    <div id="charts" class="charts"></div>
  </section>
  <section id="dash" hidden>
  <div class="sheethead">
    <a class="back" id="sheetBack" href="#/">← Back</a>
    <h2 class="dashtitle" id="dashTitle"></h2>
  </div>
  <div id="stages" class="chips"></div>
  <div class="toolbar">
    <input id="q" type="search" placeholder="Search name, phone, email, anything…" autocomplete="off"><button type="button" class="ghost" id="btnSv" title="Save this filtered view to your sidebar">★ Save view</button>
    <details class="cols" id="colsBox">
      <summary>Columns</summary>
      <div class="panel">
        <div class="row"><button class="ghost" id="colsDefault">Default</button><button class="ghost" id="colsAll">All</button></div>
        <div id="colsList"></div>
      </div>
    </details>
    <button class="ghost" id="btnCsv">Download CSV</button>
    <span class="sub" id="count"></span>
  </div>
  <div class="tablewrap"><table id="tbl"><thead></thead><tbody></tbody></table></div>
  <div class="pager">
    <button class="ghost" id="prev">Previous</button>
    <span class="sub" id="pageinfo"></span>
    <button class="ghost" id="next">Next</button>
  </div>
  </section>
  <section id="reports" hidden>
    <div class="sheethead"><a class="back" href="#/">← Dashboard</a><h2 class="dashtitle">📑 Reports</h2><div class="sub2">Pick a report. Each one has filters and clickable numbers that open the students behind them.</div></div>
    <div class="rcards" id="rpList"></div>
  </section>
  <section id="report" hidden>
    <div class="sheethead"><a class="back" href="#/reports">← All reports</a><h2 class="dashtitle" id="rpTitle"></h2><div class="sub2" id="rpDesc"></div></div>
    <div id="rpBuilder" class="rpf" hidden></div>
    <div id="rpFilters" class="rpf"></div>
    <div class="rpbar"><span class="sub" id="rpSummary"></span><span><button class="ghost" id="rpReset" type="button">Reset filters</button> <button class="ghost" id="rpXlsx" type="button" title="Excel file: click any number to jump to the deals behind it">Download Excel</button></span></div>
    <div id="rpBody"></div>
  </section>
</div>
</div>
</div>
<div id="modal" class="modal" hidden>
  <div class="mbox" role="dialog" aria-modal="true" aria-labelledby="mTitle">
    <div class="mhead">
      <div><div class="msub" id="mSub"></div><h2 id="mTitle"></h2></div>
      <button id="mClose" type="button" aria-label="Close"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
    </div>
    <div class="mtools"><input id="mFilter" type="search" placeholder="Find a field in this deal…" autocomplete="off"></div>
    <div id="mBanner" class="banner" hidden></div>
    <div class="mbody" id="mBody"></div>
    <div class="mfoot">
      <span class="sub" id="mHint">Change a value to enable Save.</span>
      <span><button class="ghost" id="mCancel" type="button">Cancel</button><button id="mSave" type="button" disabled>Save to Bitrix24</button></span>
    </div>
  </div>
</div>
<div id="toast" class="toast" hidden></div>
<script>
const CSRF = <?= json_encode($_SESSION['csrf']) ?>;
const ME = <?= json_encode((string)($_SESSION['user'] ?? '')) ?>;
const PROFILE = <?php $pf = user_profile((string)($_SESSION['user'] ?? '')); echo json_encode(array('name' => (string)($pf['name'] ?? ''), 'phone' => (string)($pf['phone'] ?? ''), 'color' => (string)($pf['color'] ?? ''), 'av' => (int)($pf['av'] ?? 0), 'views' => array_values(is_array($pf['views'] ?? null) ? $pf['views'] : array())), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG); ?>;
const AV = <?php $__u = (string)($_SESSION['user'] ?? ''); echo json_encode((object)(!empty($pf['av']) ? array($__u => (int)$pf['av']) : array()), JSON_HEX_TAG); ?>;
function avUrl(u) { return u && AV[u] ? 'api.php?action=avatar&u=' + encodeURIComponent(u) + '&v=' + AV[u] : ''; }
function avImg(u) { const x = avUrl(u); return x ? '<img src="' + x + '" alt="" loading="lazy" draggable="false">' : ''; }
function mergeAV(j) { if (j && j.avs) Object.keys(j.avs).forEach(k => { if (j.avs[k]) AV[k] = j.avs[k]; else delete AV[k]; }); }
function track(t, d) { try { fetch('api.php?action=track', {method: 'POST', credentials: 'same-origin', keepalive: true, headers: {'X-CSRF-Token': CSRF, 'Content-Type': 'application/json'}, body: JSON.stringify({t: t, d: String(d || '').slice(0, 120)})}).catch(function () {}); } catch (e) {} }
let __lastPg = '';
const SCOPE = <?= json_encode((string)(user_scope() ?? '')) ?>;
const CLOSED = <?= json_encode(isset($c['closed_stages']) && is_array($c['closed_stages']) ? array_values($c['closed_stages']) : null) ?>;
const STALE_DAYS = <?= (int)(isset($c['stale_days']) ? $c['stale_days'] : 15) ?>;
const $ = id => document.getElementById(id);
const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const PER = 100;
const LS_KEY = 'pranav_cols_v3';
const SEMRE = /^sem(ester)?\s*\d+\s*status/i;
const semNum = c => +/(\d+)/.exec(c.label)[1];
/* ---- split "Comment History(G)" into the latest comment + its date ---- */
const CM_RE = /\s*\|{1,2}\s*(\d{1,2}\/\d{1,2}\/\d{4}\s+\d{1,2}:\d{2}(?::\d{2})?\s*(?:[ap]m)?)\s*(?:\|{1,2}[ \t]*([^\r\n]*))?/gi;
function tsMs(s) {
  const m = /(\d+)\/(\d+)\/(\d+)\s+(\d+):(\d+)(?::(\d+))?\s*([ap]m)?/i.exec(s);
  if (!m) return 0;
  let h = +m[4];
  const ap = (m[7] || '').toLowerCase();
  if (ap === 'pm' && h < 12) h += 12;
  if (ap === 'am' && h === 12) h = 0;
  return Date.UTC(+m[3], +m[2] - 1, +m[1], h, +m[5], +(m[6] || 0));
}
function parseComments(raw) {
  const text = String(raw || '').replace(/\r/g, '');
  if (!text.trim()) return {c: '', at: '', ms: 0};
  const clean = t => t.split('\n').filter(l => !/^\s*-{3,}\s*$/.test(l)).join('\n').replace(/\s*\n\s*/g, ' ').trim();
  let last = 0, m, best = null;
  CM_RE.lastIndex = 0;
  while ((m = CM_RE.exec(text))) {
    const c = clean(text.slice(last, m.index));
    last = m.index + m[0].length;
    const ms = tsMs(m[1]);
    if (!best || ms > best.ms) best = {c: c, at: m[1].replace(/\s+/g, ' ').trim(), ms: ms, by: String(m[2] || '').replace(/-{3,}/g, '').trim()};
  }
  return best || {c: clean(text), at: '', ms: 0};
}
let cmSrc = null;
let TOUCHED = {};
const actOf = r => Math.max(+r._ct || 0, +TOUCHED[r.ID] || 0);
function deriveRow(r) {
  if (!cmSrc) return;
  const p = parseComments(r[cmSrc]);
  delete r._COMMENT; delete r._COMMENT_AT; delete r._COMMENT_BY;
  if (p.c) r._COMMENT = p.c;
  if (p.at) r._COMMENT_AT = p.at;
  if (p.by) r._COMMENT_BY = p.by;
  r._ct = p.ms ? String(p.ms) : '';
}
function indexRow(r) { delete r._s; r._s = Object.values(r).join(' ').toLowerCase(); }
function withComments(list, rowsArr) {
  const src = list.find(c => c.key === 'UF_CRM_1784701507') || list.find(c => /^comment history\s*\(g\)/i.test(c.label));
  cmSrc = src ? src.key : null;
  if (!src) return list;
  rowsArr.forEach(deriveRow);
  const si = list.findIndex(c => c.key === 'STAGE_ID'), fi = list.findIndex(c => SEMRE.test(c.label));
  let at = si >= 0 ? si + 1 : list.map(c => c.default).lastIndexOf(true) + 1;
  if (fi >= 0 && at > fi) at = fi;
  const out = list.slice();
  out.splice(at, 0, {key: '_COMMENT', label: 'Comments', type: 'string', default: true}, {key: '_COMMENT_BY', label: 'Updated by', type: 'string', default: true}, {key: '_COMMENT_AT', label: 'Last updated on', type: 'string', default: true});
  return out;
}
function orderCols(list) {
  const isSem = c => SEMRE.test(c.label);
  const sems = list.filter(isSem).sort((a, b) => semNum(a) - semNum(b));
  if (!sems.length) return list;
  const before = list.slice(0, list.findIndex(isSem)).filter(c => !isSem(c)).length;
  const rest = list.filter(c => !isSem(c));
  sems.forEach(c => { c.default = true; });
  rest.splice.apply(rest, [before, 0].concat(sems));
  return rest;
}
let cols = [], rows = [], visible = [], view = [], stagesList = [];
let sortKey = null, sortDir = 1, page = 0, stage = '', query = '';
let allRows = [], gfRows = [];

function lsGet() { try { return JSON.parse(localStorage.getItem(LS_KEY) || 'null'); } catch (e) { return null; } }
function lsSet(v) { try { localStorage.setItem(LS_KEY, JSON.stringify(v)); } catch (e) {} }

function showBanner(msg) {
  const b = $('banner');
  b.textContent = msg || '';
  b.hidden = !msg;
}

let fetchedAt = null, rfBusy = false;
function ago(d) {
  const m = Math.floor((Date.now() - d.getTime()) / 60000);
  if (m < 1) return 'just now';
  if (m < 60) return m + ' min ago';
  const h = Math.floor(m / 60);
  return h + ' h ' + (m % 60) + ' min ago';
}
function showUpdated() {
  if (rfBusy || !fetchedAt || !window._cnt) return;
  const u = $('updated');
  u.textContent = 'Updated ' + ago(fetchedAt) + ' · ' + window._cnt.toLocaleString() + ' deals';
  { const m = (Date.now() - fetchedAt.getTime()) / 60000; u.className = 'sub' + (m >= 120 ? ' old' : m >= 30 ? ' warn' : ''); u.title = m >= 30 ? 'This data is ' + ago(fetchedAt).replace(' ago', '') + ' old. Press Refresh data before quoting numbers.' : ''; }
  u.title = 'Last data fetch: ' + fetchedAt.toLocaleString() + '. The page refreshes itself every 5 minutes.';
}
async function load(keep) {
  const r = await fetch('api.php?action=data', {credentials: 'same-origin'});
  if (r.status === 401) { location.reload(); return; }
  const j = await r.json();
  if (j.last_error) {
    showBanner('The last refresh failed (' + j.last_error.message + '). Showing the previous data.');
  } else {
    showBanner('');
  }
  if (!j.data) {
    cols = []; rows = []; allRows = []; stagesList = [];
    $('updated').textContent = 'No data yet. Press "Refresh data".';
    renderAll();
    return;
  }
  cols = orderCols(j.data.columns);
  rows = j.data.rows;
  TOUCHED = (j.touched && typeof j.touched === 'object') ? j.touched : {};
  cols = cols.filter(c => !OLD_DOC_RES.some(re => re.test(c.label)));
  cols = withComments(cols, rows);
  const needsRefresh = !cols.some(c => 'editable' in c);
  stagesList = j.data.stages || [];
  rows.forEach(indexRow);
  allRows = rows;
  const keys = new Set(cols.map(c => c.key));
  const saved = lsGet();
  visible = (saved && saved.filter(k => keys.has(k)).length) ? saved.filter(k => keys.has(k)) : cols.filter(c => c.default).map(c => c.key);
  // One-time: people who saved their own column choice get the new "Updated by" column right after Comments.
  try { if (!localStorage.getItem('pranav_by_col') && keys.has('_COMMENT_BY')) { localStorage.setItem('pranav_by_col', '1'); if (visible.indexOf('_COMMENT_BY') < 0) { const i = visible.indexOf('_COMMENT'); if (i >= 0) { visible.splice(i + 1, 0, '_COMMENT_BY'); lsSet(visible); } } } } catch (e) {}
  fetchedAt = new Date(j.data.fetched_at); window._cnt = j.data.count;
  showUpdated();
  if (!keep) page = 0;
  renderAll();
  if (needsRefresh && !j.last_error) showBanner('To turn on editing, press "Refresh data" once.');
}

const UNASSIGNED = '(No university)';
let route = {type: 'home', name: ''}, baseList = [];

function uniKey() {
  if (cols.some(c => c.key === 'UF_CRM_1784702446')) return 'UF_CRM_1784702446';
  const c = cols.find(c => /university/i.test(c.label));
  return c ? c.key : null;
}
function uniOf(r) {
  const k = uniKey();
  const v = k ? String(r[k] || '').trim() : '';
  return v === '' ? UNASSIGNED : v;
}
function hashFor(name, f, sheet) {
  let h = name === null ? '#/master' : '#/u/' + encodeURIComponent(name);
  if (sheet) h += '/sheet';
  const q = [];
  ['p', 'm', 's', 'f', 'q'].forEach(k => { if (f && f[k] != null) q.push(k + '=' + encodeURIComponent(f[k])); });
  if (f && f.x) f.x.forEach(p => q.push('x=' + encodeURIComponent(p[0] + '=' + p[1])));
  if (f && f.rb) q.push('rb=' + encodeURIComponent(f.rb));
  if (f && f.bot) q.push('bot=' + encodeURIComponent(f.bot));
  return h + (q.length ? '?' + q.join('&') : '');
}
function parseRoute() {
  let h = location.hash.replace(/^#/, '');
  let qs = '';
  const qi = h.indexOf('?');
  if (qi >= 0) { qs = h.slice(qi + 1); h = h.slice(0, qi); }
  const r = {type: 'home', name: '', sheet: false, p: null, m: null, s: null, f: null, q: null, x: [], rb: null, bot: null, id: '', rq: {}};
  let mt;
  if (/^\/reports\/?$/.test(h)) { r.type = 'reports'; return r; }
  if ((mt = /^\/reports\/([\w-]+)$/.exec(h))) { r.type = 'report'; r.id = mt[1]; r.rq = Object.fromEntries(new URLSearchParams(qs)); return r; }
  if ((mt = /^\/master(\/sheet)?$/.exec(h))) {
    r.type = 'master'; r.sheet = !!mt[1];
  } else if ((mt = /^\/u\/([^/]*)(\/sheet)?$/.exec(h))) {
    try { r.name = decodeURIComponent(mt[1]); r.type = 'uni'; r.sheet = !!mt[2]; } catch (e) {}
  }
  if (r.type !== 'home' && r.sheet) {
    const sp = new URLSearchParams(qs);
    ['p', 'm', 's', 'f', 'q'].forEach(k => { r[k] = sp.has(k) ? sp.get(k) : null; });
    r.bot = sp.get('bot');
    r.x = sp.getAll('x').map(t => { const i = t.indexOf('='); return [t.slice(0, i), t.slice(i + 1)]; }).filter(p => p[0]);
    const rb = sp.get('rb'); r.rb = rb && /^#\/reports/.test(rb) ? rb : null;
  }
  return r;
}
function baseRows() { return baseList; }
const scopeName = () => route.type === 'uni' ? route.name : null;
const scopeTitle = () => { if (route.type !== 'uni') return 'Master dashboard'; const n = route.name.split(MS); return n.length > 3 ? n.length + ' universities' : n.join(' + '); };

const NONE = '(none)';
function colKey(exact, re) {
  if (cols.some(c => c.key === exact)) return exact;
  const c = cols.find(c => re.test(c.label));
  return c ? c.key : null;
}
const progKey = () => colKey('UF_CRM_1784702837', /program|course/i);
const modeKey = () => colKey('UF_CRM_1784712099', /mode.*payment|payment.*mode/i);
const batchKey = () => colKey('UF_CRM_1784713245', /batch/i);
function cellVal(r, k) { const v = String(r[k] || '').trim(); return v === '' ? NONE : v; }
function colLabel(k) { const c = cols.find(c => c.key === k); return c ? c.label : k; }
const sortNames = a => Object.keys(a).sort((x, y) => x === NONE ? 1 : y === NONE ? -1 : x.localeCompare(y, undefined, {numeric: true, sensitivity: 'base'}));
const fmtN = n => n.toLocaleString();

function tally(list, keyFn) {
  const t = {};
  list.forEach(r => { const k = keyFn(r); t[k] = (t[k] || 0) + 1; });
  return t;
}
function byCountDesc(t) { return Object.keys(t).sort((a, b) => t[b] - t[a] || a.localeCompare(b)); }

/* ---------- matrix (programs x mode of payment) ---------- */
function renderMatrix() {
  const pk = progKey(), mk = modeKey(), box = $('matrix'), list = baseRows();
  if (!pk || !mk || !list.length) { box.innerHTML = ''; box.hidden = true; return; }
  const cnt = {}, pt = {}, mt = {};
  list.forEach(r => {
    const p = cellVal(r, pk), m = cellVal(r, mk);
    (cnt[p] = cnt[p] || {})[m] = (cnt[p][m] || 0) + 1;
    pt[p] = (pt[p] || 0) + 1; mt[m] = (mt[m] || 0) + 1;
  });
  const progs = sortNames(pt), modes = sortNames(mt);
  const maxCell = Math.max.apply(null, progs.map(p => Math.max.apply(null, modes.map(m => (cnt[p] && cnt[p][m]) || 0))).concat([1]));
  const maxRow = Math.max.apply(null, progs.map(p => pt[p]).concat([1]));
  const btn = (p, m, label, extra, inner, style) => '<button class="mxb' + (extra || '') + '"' + (style ? ' style="' + style + '"' : '') +
    (p !== null ? ' data-p="' + esc(p) + '"' : '') + (m !== null ? ' data-m="' + esc(m) + '"' : '') + '>' + (inner || esc(label)) + '</button>';
  let h = '<div class="cap"><b>' + esc(colLabel(pk)) + ' × ' + esc(colLabel(mk)) + '</b><span class="sub">Click a number, a program or a payment mode to open those deals as a full-page sheet.</span></div>';
  h += '<div class="mxscroll"><table class="mx" style="min-width:' + (420 + 92 * modes.length) + 'px"><colgroup><col style="width:230px">' + modes.map(() => '<col>').join('') + '<col style="width:190px"></colgroup><thead><tr><th class="l">' + btn(null, null, 'All deals', ' h rh') + '</th>';
  modes.forEach(m => { h += '<th>' + btn(null, m, m, ' h') + '</th>'; });
  h += '<th class="tot">Total</th></tr></thead><tbody>';
  progs.forEach(p => {
    h += '<tr><td class="l">' + btn(p, null, p, ' h rh', '<i class="cd" style="background:' + hashColor(p) + '"></i>' + esc(p)) + '</td>';
    modes.forEach(m => {
      const n = (cnt[p] && cnt[p][m]) || 0;
      h += n ? '<td>' + btn(p, m, fmtN(n), ' heat' + (Math.sqrt(n / maxCell) > 0.6 ? ' dk' : ''), null, '--i:' + (0.14 + 0.76 * Math.sqrt(n / maxCell)).toFixed(2)) + '</td>' : '<td class="zero"><span>·</span></td>';
    });
    h += '<td class="tot"><div class="tb">' + btn(p, null, '', ' h tn', '<span>' + fmtN(pt[p]) + '</span>') + '<i style="width:' + Math.max(3, Math.round(pt[p] / maxRow * 90)) + 'px"></i></div></td></tr>';
  });
  h += '<tr><td class="l tot">Total</td>';
  modes.forEach(m => { h += '<td class="tot ctr">' + btn(null, m, fmtN(mt[m]), ' h tn') + '</td>'; });
  h += '<td class="tot"><div class="tb">' + btn(null, null, '', ' h tn', '<span>' + fmtN(list.length) + '</span>') + '</div></td></tr></tbody></table></div>';
  box.innerHTML = h;
  box.hidden = false;
}
$('matrix').addEventListener('click', e => {
  const b = e.target.closest('button.mxb');
  if (!b) return;
  const p = b.hasAttribute('data-p') ? b.getAttribute('data-p') : null;
  const m = b.hasAttribute('data-m') ? b.getAttribute('data-m') : null;
  location.hash = hashFor(scopeName(), {p: p, m: m}, true);
});

/* ---------- charts ---------- */
const SERIES = 7;
const MON = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
let SVG_W = 640;
function trunc(s, n) { s = String(s); return s.length > n ? s.slice(0, n - 1) + '…' : s; }
function niceStep(raw) {
  raw = Math.max(raw, 1);
  const p = Math.pow(10, Math.floor(Math.log10(raw))), f = raw / p;
  return (f <= 1 ? 1 : f <= 2 ? 2 : f <= 4 ? 4 : f <= 5 ? 5 : 10) * p;
}
function axisScale(max) {
  const step = niceStep(max / 4), top = Math.max(step, Math.ceil(max / step) * step), t = [];
  for (let v = 0; v <= top + 1e-9; v += step) t.push(v);
  return {top: top, ticks: t};
}
function legendHtml(series) {
  return '<div class="lgd">' + series.map(s => '<span class="lg"><i style="background:' + s.color + '"></i>' + esc(s.label) + '</span>').join('') + '</div>';
}
/* cats: [{label, tip, segs:[{sk, n, href}]}]; series: [{key,label,color}] */
function stackH(cats, series, xTitle, yTitle, noLegend) {
  const L = 128, R = 34, T = 8, B = 50, rowH = 34;
  const H = T + cats.length * rowH + B, W = SVG_W;
  const tot = c => c.segs.reduce((a, s) => a + s.n, 0);
  const sc = axisScale(Math.max.apply(null, cats.map(tot).concat([1])));
  const X = v => L + (W - L - R) * v / sc.top;
  const byKey = {}; series.forEach(s => { byKey[s.key] = s; });
  let g = '';
  sc.ticks.forEach(v => {
    const x = X(v).toFixed(1);
    g += '<line class="grid" x1="' + x + '" x2="' + x + '" y1="' + T + '" y2="' + (H - B) + '"/><text class="ax" x="' + x + '" y="' + (H - B + 15) + '" text-anchor="middle">' + fmtN(v) + '</text>';
  });
  cats.forEach((c, i) => {
    const y = T + i * rowH + 6, bh = rowH - 12;
    const lt = '<text class="ax cat" x="' + (L - 8) + '" y="' + (y + bh / 2 + 4) + '" text-anchor="end"><title>' + esc(c.label) + '</title>' + esc(trunc(c.label, 17)) + '</text>';
    g += c.href ? '<a class="lnk" href="' + esc(c.href) + '">' + lt + '</a>' : lt;
    let acc = 0;
    c.segs.forEach(s => {
      if (!s.n) return;
      const ser = byKey[s.sk];
      const rect = '<rect class="seg" x="' + X(acc).toFixed(1) + '" y="' + y + '" width="' + Math.max(1, X(acc + s.n) - X(acc)).toFixed(1) + '" height="' + bh + '" rx="3" fill="' + ser.color + '"><title>' + esc(c.label) + ' · ' + esc(ser.label) + ': ' + fmtN(s.n) + ' deals</title></rect>';
      g += s.href ? '<a href="' + esc(s.href) + '">' + rect + '</a>' : rect;
      acc += s.n;
    });
    const tt = '<text class="tv" x="' + (X(acc) + 6).toFixed(1) + '" y="' + (y + bh / 2 + 4) + '">' + fmtN(acc) + '</text>';
    g += c.href ? '<a class="lnk" href="' + esc(c.href) + '">' + tt + '</a>' : tt;
  });
  g += '<text class="at" x="' + ((L + W - R) / 2) + '" y="' + (H - 6) + '" text-anchor="middle">' + esc(xTitle) + '</text>';
  g += '<text class="at" transform="translate(12 ' + ((T + H - B) / 2) + ') rotate(-90)" text-anchor="middle">' + esc(yTitle) + '</text>';
  return (noLegend ? '' : legendHtml(series)) + '<svg viewBox="0 0 ' + W + ' ' + H + '" class="stk" role="img" aria-label="' + esc(yTitle + ' by ' + xTitle) + '">' + g + '</svg>';
}
function stackV(cats, series, xTitle, yTitle) {
  const L = 52, R = 12, T = 12, B = 52, H = 300, W = SVG_W;
  const tot = c => c.segs.reduce((a, s) => a + s.n, 0);
  const sc = axisScale(Math.max.apply(null, cats.map(tot).concat([1])));
  const Y = v => T + (H - T - B) * (1 - v / sc.top);
  const slot = (W - L - R) / cats.length, bw = Math.min(64, slot * 0.6);
  const byKey = {}; series.forEach(s => { byKey[s.key] = s; });
  let g = '';
  sc.ticks.forEach(v => {
    const y = Y(v).toFixed(1);
    g += '<line class="grid" x1="' + L + '" x2="' + (W - R) + '" y1="' + y + '" y2="' + y + '"/><text class="ax" x="' + (L - 7) + '" y="' + (+y + 4) + '" text-anchor="end">' + fmtN(v) + '</text>';
  });
  cats.forEach((c, i) => {
    const cx = L + slot * i + slot / 2;
    let acc = 0;
    c.segs.forEach(s => {
      if (!s.n) return;
      const ser = byKey[s.sk], y1 = Y(acc + s.n), y0 = Y(acc);
      const rect = '<rect class="seg" x="' + (cx - bw / 2).toFixed(1) + '" y="' + y1.toFixed(1) + '" width="' + bw.toFixed(1) + '" height="' + Math.max(1, y0 - y1).toFixed(1) + '" rx="3" fill="' + ser.color + '"><title>' + esc(c.label) + ' · ' + esc(ser.label) + ': ' + fmtN(s.n) + '</title></rect>';
      g += s.href ? '<a href="' + esc(s.href) + '">' + rect + '</a>' : rect;
      acc += s.n;
    });
    g += '<text class="tv" x="' + cx.toFixed(1) + '" y="' + (Y(acc) - 5).toFixed(1) + '" text-anchor="middle">' + fmtN(acc) + '</text>';
    g += '<text class="ax" x="' + cx.toFixed(1) + '" y="' + (H - B + 16) + '" text-anchor="middle">' + esc(trunc(c.label, 12)) + '</text>';
  });
  g += '<text class="at" x="' + ((L + W - R) / 2) + '" y="' + (H - 6) + '" text-anchor="middle">' + esc(xTitle) + '</text>';
  g += '<text class="at" transform="translate(12 ' + ((T + H - B) / 2) + ') rotate(-90)" text-anchor="middle">' + esc(yTitle) + '</text>';
  return legendHtml(series) + '<svg viewBox="0 0 ' + W + ' ' + H + '" class="stk" role="img" aria-label="' + esc(yTitle + ' by ' + xTitle) + '">' + g + '</svg>';
}
function lineHtml(list, n) {
  const dk = colKey('DATE_CREATE', /^created/i);
  if (!dk) return '';
  const t = {};
  list.forEach(r => { const m = /^(\d{4})-(\d{2})/.exec(String(r[dk] || '')); if (m) t[m[1] + '-' + m[2]] = (t[m[1] + '-' + m[2]] || 0) + 1; });
  const keys = n ? lastMonths(n) : Object.keys(t).sort();
  keys.forEach(k => { if (!t[k]) t[k] = 0; });
  if (keys.length < 2 || (n && !Object.keys(t).some(k => t[k]))) return '';
  const W = SVG_W, H = 300, L = 52, R = 30, T = 14, B = 56;
  const sc = axisScale(Math.max.apply(null, keys.map(k => t[k])));
  const X = i => L + (W - L - R) * i / (keys.length - 1);
  const Y = v => T + (H - T - B) * (1 - v / sc.top);
  const lab = k => MON[+k.slice(5) - 1] + " '" + k.slice(2, 4);
  const pts = keys.map((k, i) => X(i).toFixed(1) + ',' + Y(t[k]).toFixed(1));
  let g = '';
  sc.ticks.forEach(v => {
    const y = Y(v).toFixed(1);
    g += '<line class="grid" x1="' + L + '" x2="' + (W - R) + '" y1="' + y + '" y2="' + y + '"/><text class="ax" x="' + (L - 7) + '" y="' + (+y + 4) + '" text-anchor="end">' + fmtN(v) + '</text>';
  });
  const step = Math.ceil(keys.length / 8);
  keys.forEach((k, i) => { if (i % step === 0 || i === keys.length - 1) g += '<text class="ax" x="' + X(i).toFixed(1) + '" y="' + (H - B + 16) + '" text-anchor="middle">' + lab(k) + '</text>'; });
  const area = 'M' + X(0).toFixed(1) + ',' + Y(0).toFixed(1) + ' L' + pts.join(' L') + ' L' + X(keys.length - 1).toFixed(1) + ',' + Y(0).toFixed(1) + ' Z';
  const mh = k => hashFor(scopeName(), {x: [['_MON', k]]}, true);
  const dots = keys.map((k, i) => { const c = '<circle class="pt" cx="' + X(i).toFixed(1) + '" cy="' + Y(t[k]).toFixed(1) + '" r="' + (t[k] ? 6 : 4.5) + '"><title>' + lab(k) + ': ' + fmtN(t[k]) + ' deals' + (t[k] ? ' · click to open' : '') + '</title></circle>'; return t[k] ? '<a class="lnk" href="' + esc(mh(k)) + '">' + c + '</a>' : c; }).join('');
  const vals = keys.map((k, i) => { const tx = '<text class="tv" x="' + X(i).toFixed(1) + '" y="' + (Y(t[k]) - 10).toFixed(1) + '" text-anchor="middle">' + fmtN(t[k]) + '</text>'; return t[k] ? '<a class="lnk" href="' + esc(mh(k)) + '">' + tx + '</a>' : tx; }).join('');
  g += '<text class="at" x="' + ((L + W - R) / 2) + '" y="' + (H - 6) + '" text-anchor="middle">Month the deal was created</text>';
  g += '<text class="at" transform="translate(12 ' + ((T + H - B) / 2) + ') rotate(-90)" text-anchor="middle">Number of deals</text>';
  return '<svg viewBox="0 0 ' + W + ' ' + H + '" class="stk line" role="img" aria-label="Deals created per month">' + g + '<path class="area" d="' + area + '"/><polyline class="ln1" points="' + pts.join(' ') + '"/>' + dots + (keys.length <= 14 ? vals : '') + '</svg>';
}
function card(title, sub, body, cls) {
  return '<div class="chartcard' + (cls ? ' ' + cls : '') + '"><h3>' + esc(title) + '</h3>' + (sub ? '<div class="sub">' + esc(sub) + '</div>' : '') + body + '</div>';
}

function renderKpis() {
  const list = baseRows(), pk = progKey();
  const tiles = [['Deals', fmtN(list.length), hashFor(scopeName(), null, true)]];
  if (route.type === 'master') {
    tiles.push(['Universities', fmtN(Object.keys(tally(list, uniOf)).filter(n => n !== UNASSIGNED).length)]);
  }
  tiles.push(['Active deals', fmtN(list.filter(r => !isClosed(r)).length), hashFor(scopeName(), {f: 'active'}, true)]);
  tiles.push(['Payment overdue', fmtN(list.filter(r => passF(r, 'paydue')).length), hashFor(scopeName(), {f: 'paydue'}, true)], ['Documents pending', fmtN(list.filter(r => passF(r, 'docpend')).length), hashFor(scopeName(), {f: 'docpend'}, true)]);
  if (cmSrc) tiles.push(['Follow-up overdue', fmtN(list.filter(isStale).length), hashFor(scopeName(), {f: 'stale'}, true)], ['Not contacted', fmtN(list.filter(isNotContacted).length), hashFor(scopeName(), {f: 'notcontacted'}, true)]);
  if (pk) tiles.push(['Programs', fmtN(Object.keys(tally(list, r => cellVal(r, pk))).filter(n => n !== NONE).length)]);
  const ak = colKey('OPPORTUNITY', /^amount$|^opportunity$/i);
  if (ak) {
    const sum = list.reduce((a, r) => a + (parseFloat(r[ak]) || 0), 0);
    if (sum > 0) tiles.push([colLabel(ak), sum.toLocaleString('en-IN', {maximumFractionDigits: 0})]);
  }
  $('kpis').innerHTML = tiles.map((t, i) => t[2]
    ? '<a class="kpi" style="--c:var(--s' + (i % 5 + 1) + ')" href="' + esc(t[2]) + '"><span>' + esc(t[0]) + '</span><b>' + esc(t[1]) + '</b></a>'
    : '<div class="kpi" style="--c:var(--s' + (i % 5 + 1) + ')"><span>' + esc(t[0]) + '</span><b>' + esc(t[1]) + '</b></div>').join('');
}

function renderCharts() {
  const list = baseRows(), sc = scopeName(), pk = progKey();
  let h = '';
  // 1) Programs x pipeline stage
  if (pk && list.length) {
    const seen = tally(list, r => r.STAGE_ID || '(no stage)');
    const order = stagesList.concat(Object.keys(seen).filter(s => stagesList.indexOf(s) < 0));
    const idx = n => order.indexOf(n);
    const stageSeries = order.filter(n => seen[n] && idx(n) < SERIES).map(n => ({key: n, label: n, color: 'var(--s' + (idx(n) + 1) + ')'}));
    const otherStages = order.filter(n => seen[n] && idx(n) >= SERIES);
    const hasOther = otherStages.length > 0;
    if (hasOther) stageSeries.push({key: '__other', label: 'Other stages', color: 'var(--other)'});
    const perProg = {};
    list.forEach(r => {
      const p = cellVal(r, pk), s = r.STAGE_ID || '(no stage)', k = idx(s) < SERIES ? s : '__other';
      ((perProg[p] = perProg[p] || {})[k] = (perProg[p][k] || 0) + 1);
    });
    const tot = p => Object.keys(perProg[p]).reduce((a, k) => a + perProg[p][k], 0);
    let names = Object.keys(perProg).sort((a, b) => tot(b) - tot(a) || a.localeCompare(b));
    const cats = names.slice(0, 10).map(p => ({label: p, href: hashFor(sc, {p: p}, true), segs: stageSeries.map(s => ({sk: s.key, n: perProg[p][s.key] || 0, href: s.key === '__other' ? hashFor(sc, {p: p, s: otherStages.join(MS)}, true) : hashFor(sc, {p: p, s: s.key}, true)}))}));
    if (names.length > 10) {
      const rest = {};
      names.slice(10).forEach(p => Object.keys(perProg[p]).forEach(k => { rest[k] = (rest[k] || 0) + perProg[p][k]; }));
      const restNames = names.slice(10), rp = [[pk, restNames.join(MS)]];
      cats.push({label: 'Other programs (' + (names.length - 10) + ')', href: hashFor(sc, {x: rp}, true), segs: stageSeries.map(s => ({sk: s.key, n: rest[s.key] || 0, href: hashFor(sc, {x: rp.concat([['STAGE_ID', s.key === '__other' ? otherStages.join(MS) : s.key]])}, true)}))});
    }
    h += card('Pipeline stage of every program', 'Each bar is one program. The colours show which stage its deals are in. Click a colour block to open those deals.',
      stackH(cats, stageSeries, 'Number of deals', colLabel(pk)), 'wide');
  }
  // 2) Semester payment status
  let semKeys = cols.filter(c => SEMRE.test(c.label)).sort((a, b) => semNum(a) - semNum(b)).map(c => c.key);
  if (!semKeys.length) semKeys = ['UF_CRM_1784188741648', 'UF_CRM_1784188780998', 'UF_CRM_1784188805532', 'UF_CRM_1784188830072', 'UF_CRM_1784188849279', 'UF_CRM_1784188886587'].filter(k => cols.some(c => c.key === k));
  if (semKeys.length && list.length) {
    const vals = {};
    semKeys.forEach(k => list.forEach(r => { const v = cellVal(r, k); vals[v] = (vals[v] || 0) + 1; }));
    const vnames = Object.keys(vals).sort((a, b) => {
      const w = n => n === NONE ? 9 : /^paid$/i.test(n) ? 0 : /not/i.test(n) ? 1 : 2;
      return w(a) - w(b) || a.localeCompare(b);
    });
    const spare = ['var(--s1)', 'var(--s4)', 'var(--s5)', 'var(--s7)'];
    const semSeries = vnames.map(v => ({key: v, label: v === NONE ? 'Not updated' : v,
      color: /^paid$/i.test(v) ? 'var(--s3)' : /not/i.test(v) ? 'var(--s2)' : v === NONE ? 'var(--other)' : (spare.shift() || 'var(--other)')}));
    const cats = semKeys.map(k => {
      const t = tally(list, r => cellVal(r, k));
      return {label: colLabel(k).replace(/\s*status\s*$/i, ''), segs: semSeries.map(s => ({sk: s.key, n: t[s.key] || 0, href: hashFor(sc, {x: [[k, s.key]]}, true)}))};
    });
    h += card('Semester fee status', 'How many students have paid, not paid or not been updated in each semester.', stackV(cats, semSeries, 'Semester', 'Number of students'));
  }
  // 3) Monthly trend
  const ln = lineHtml(list);
  if (ln) h += card('New deals per month', 'How many deals were created in each month. Hover a dot for details.', ln);
  $('charts').innerHTML = h;
}

function renderOverview() {
  $('ovTitle').textContent = scopeTitle();
  $('ovAll').href = hashFor(scopeName(), null, true);
  $('ovAll').textContent = 'View all ' + fmtN(baseRows().length) + ' deals →';
  renderKpis();
  renderCharts();
  renderMatrix();
}

/* ---- global filters (shown on every page except reports) ---- */
const GF_KEY = 'pranav_gf';
let GF = (function () { try { return JSON.parse(localStorage.getItem(GF_KEY) || '{}') || {}; } catch (e) { return {}; } })();
function gfSave() { try { localStorage.setItem(GF_KEY, JSON.stringify(GF)); } catch (e) {} }
function gfActive() { return fieldDefs().filter(f => GF[f.id] && GF[f.id].length && !(f.id === 'uni' && route.type === 'uni')); }
function gfCompute() {
  const act = gfActive();
  gfRows = act.length ? allRows.filter(r => act.every(f => GF[f.id].indexOf(fv(r, f.key)) >= 0)) : allRows;
}
function placeGF(home) {
  const bar = $('gfbar'), hero = $('hero');
  if (home && hero) { if (bar.previousElementSibling !== hero || bar.parentNode !== hero.parentNode) hero.after(bar); }
  else if (bar.parentNode !== document.querySelector('.wrap').parentNode) document.querySelector('.wrap').before(bar);
}
function renderGF() {
  const bar = $('gfbar');
  const useAll = route.type === 'reports' || route.type === 'report' || !!route.rb || !!(route.sheet && route.bot);
  bar.hidden = useAll || !allRows.length;
  if (bar.hidden) return;
  const defs = fieldDefs().filter(f => !(f.id === 'uni' && route.type === 'uni'));
  bar.innerHTML = '<span class="gft">Filters</span>' + defs.map(f => {
    const vals = new Set(); allRows.forEach(r => vals.add(fv(r, f.key)));
    const cur = (GF[f.id] || []).filter(v => vals.has(v));
    return '<label>' + esc(f.label) + '<select data-g="' + esc(f.id) + '" data-v="' + esc(cur.join(MS)) + '" multiple>' + orderVals(f.key, vals).map(v => '<option value="' + esc(v) + '">' + esc(v) + '</option>').join('') + '</select></label>';
  }).join('') + '<span class="gfs" id="gfsum"></span><button type="button" class="ghost" id="gfclr">Clear filters</button>';
  bar.querySelectorAll('select[data-g]').forEach(enhanceMulti);
  const act = gfActive();
  $('gfclr').hidden = !act.length;
  $('gfsum').textContent = act.length ? 'Showing ' + fmtN(rows.length) + ' of ' + fmtN(allRows.length) + ' deals' : '';
  bar.classList.toggle('on', act.length > 0);
}
$('gfbar').addEventListener('change', e => {
  const sel = e.target.closest('select[data-g]'); if (!sel) return;
  GF[sel.dataset.g] = sel.dataset.v ? sel.dataset.v.split(MS) : [];
  gfSave(); page = 0; renderRoute(false);
});
$('gfbar').addEventListener('click', e => { if (e.target.closest('#gfclr')) { GF = {}; gfSave(); page = 0; renderRoute(false); } });
function botSel(key) {
  let M = window.__botSel;
  if (!M || !M[key]) { try { M = Object.assign({}, JSON.parse(sessionStorage.getItem('pranav_botsel') || '{}'), M || {}); } catch (e) { M = M || {}; } window.__botSel = M; }
  const o = M[key]; if (!o) return null;
  if (!o.set) { try { Object.defineProperty(o, 'set', {value: new Set(o.ids), enumerable: false}); } catch (e) {} }
  return {label: o.label, ids: o.set || new Set(o.ids)};
}
function renderRoute(reset) {
  route = parseRoute();
  try { const pk = route.type + (route.name ? ':' + route.name : '') + (route.id ? ':' + route.id : '') + (route.sheet ? ' (table)' : ''); if (pk !== __lastPg) { __lastPg = pk; track('view', pk); } } catch (e) {}
  try { if (location.hash === '#/' || location.hash === '#') history.replaceState(null, '', location.pathname.replace(/\/index\.php$/, '/') + location.search); } catch (e) {}
  gfCompute();
  rows = (route.type === 'reports' || route.type === 'report' || route.rb || (route.sheet && route.bot)) ? allRows : gfRows;
  placeGF(route.type === 'home');
  renderGF();
  if (route.type === 'reports' || route.type === 'report') { baseList = rows; showReportsView(reset); return; }
  $('reports').hidden = true; $('report').hidden = true;
  baseList = route.type === 'uni' ? rows.filter(r => route.name.split(MS).indexOf(uniOf(r)) >= 0) : rows;
  if (route.sheet && route.f) baseList = baseList.filter(r => passF(r, route.f));
  if (route.sheet && route.x.length) baseList = baseList.filter(r => route.x.every(p => xMatch(r, p)));
  if (route.sheet && route.bot) { const bs = botSel(route.bot); if (bs) baseList = allRows.filter(r => bs.ids.has(String(r.ID))); }
  if (reset) { stage = route.s || ''; query = route.q || ''; $('q').value = query; page = 0; sortKey = null; sortDir = 1; window.scrollTo(0, 0); }
  const isHome = route.type === 'home';
  $('home').hidden = !isHome;
  $('overview').hidden = isHome || route.sheet;
  $('dash').hidden = isHome || !route.sheet;
  document.querySelector('.wrap').classList.toggle('full', !isHome && route.sheet);
  $('shell').classList.toggle('nosb', !isHome && route.sheet);
  renderSide();
  if (isHome) {
    document.title = 'Pranav Pipeline';
    renderHome();
  } else if (!route.sheet) {
    document.title = scopeTitle() + ' · Pranav Pipeline';
    renderOverview();
  } else {
    const parts = [scopeTitle()];
    if (route.p !== null) parts.push(route.p);
    if (route.m !== null) parts.push(route.m);
    if (route.s !== null) parts.push(route.s.split(MS).join(' or '));
    if (route.f && FILTER_LABEL[route.f]) parts.push(FILTER_LABEL[route.f]);
    route.x.forEach(p => parts.push(xLabel(p)));
    if (route.bot) { const bs = botSel(route.bot); parts.push(bs ? bs.label : 'Selection from Pranav (expired, showing all)'); }
    if (route.q) parts.push('Search: ' + route.q);
    $('dashTitle').textContent = parts.join(' · ');
    document.title = parts.join(' · ') + ' · Pranav Pipeline';
    $('sheetBack').href = route.rb || hashFor(scopeName(), null, false);
    $('sheetBack').textContent = route.rb ? '← Back to report' : '← Back to ' + scopeTitle();
    renderStages();
    apply();
  }
}

/* ---------- app shell: sidebar, global search, home ---------- */
const STALE_LABEL = 'Follow-up overdue (' + STALE_DAYS + '+ days)';
const FILTER_LABEL = {paydue: 'Payment overdue (next payment date passed)', docpend: 'Documents pending', active: 'Active deals', stale: STALE_LABEL, nocomment: 'No comment yet', notcontacted: 'Not contacted (no comment yet)'};
function isClosed(r) {
  const s = r.STAGE_ID || '';
  if (CLOSED) return CLOSED.indexOf(s) >= 0;
  return /complet|won|closed|dropp|lost|junk|cancel|reject|not interested/i.test(s);
}
const nowLocal = () => Date.now() - new Date().getTimezoneOffset() * 60000;
// Alumni (stages grouped as Completed) never need a follow-up
const isAlumni = r => stageGroup(r.STAGE_ID || '') === GRP[1];
// Has the deal ever been commented on? (Comment History empty, or only dashes, means never contacted.)
const hasHist = r => !!cmSrc && String(r[cmSrc] || '').replace(/^\s*-{3,}\s*$/gm, '').trim() !== '';
// Overdue: there is a comment history and the last comment is 15+ days old (or has no readable date).
function isStale(r) { return !isClosed(r) && !isAlumni(r) && hasHist(r) && (!r._ct || (nowLocal() - +r._ct) / 864e5 >= STALE_DAYS); }
// Not contacted: Comment History is empty.
function isNotContacted(r) { return !isClosed(r) && !isAlumni(r) && !hasHist(r); }
function passF(r, f) {
  if (f === 'active') return !isClosed(r);
  if (f === 'stale') return isStale(r);
  if (f === 'paydue') return !isClosed(r) && rrStatus(r) === RR.O;
  if (f === 'docpend') return !isClosed(r) && !isAlumni(r) && docStatus(r) === 'Pending';
  if (f === 'notcontacted') return isNotContacted(r);
  if (f === 'nocomment') return !isClosed(r) && !isAlumni(r) && !r._ct;
  return true;
}
const createdKey = () => colKey('DATE_CREATE', /^created/i);
function monthOf(r, dk) { const m = dk && /^(\d{4})-(\d{2})/.exec(String(r[dk] || '')); return m ? m[1] + '-' + m[2] : ''; }
function lastMonths(n) {
  const out = [], t = new Date();
  let y = t.getFullYear(), m = t.getMonth();
  for (let i = 0; i < n; i++) { out.unshift(y + '-' + String(m + 1).padStart(2, '0')); m--; if (m < 0) { m = 11; y--; } }
  return out;
}
function monthlyCounts(list, n) {
  const dk = createdKey(), t = {};
  if (dk) list.forEach(r => { const k = monthOf(r, dk); if (k) t[k] = (t[k] || 0) + 1; });
  return lastMonths(n).map(k => ({k: k, n: t[k] || 0}));
}
function spark(vals, color) {
  const W = 120, H = 44, max = Math.max.apply(null, vals.concat([1]));
  const X = i => vals.length < 2 ? 0 : 3 + (W - 6) * i / (vals.length - 1), Y = v => H - 4 - (H - 10) * v / max;
  const pts = vals.map((v, i) => X(i).toFixed(1) + ',' + Y(v).toFixed(1));
  return '<svg class="sp" viewBox="0 0 ' + W + ' ' + H + '" style="--c:' + color + '"><path class="a" d="M' + X(0).toFixed(1) + ',' + (H - 4) + ' L' + pts.join(' L') + ' L' + X(vals.length - 1).toFixed(1) + ',' + (H - 4) + ' Z"/><polyline class="l" points="' + pts.join(' ') + '"/></svg>';
}
function initials(n) {
  const w = String(n).replace(/[^A-Za-z0-9 ]/g, ' ').trim().split(/\s+/).filter(Boolean);
  return (w.length > 1 ? w[0][0] + w[1][0] : (w[0] || '?').slice(0, 2)).toUpperCase();
}
function donutHtml(items, total, hrefFn) {
  const C = 100, gap = items.length > 1 ? 0.8 : 0;
  let off = 0, segs = '';
  items.forEach((it, i) => {
    const len = it.n / total * C, col = it.other ? 'var(--other)' : 'var(--s' + (i + 1) + ')';
    it.color = col;
    const pct = Math.round(it.n / total * 1000) / 10, dl = Math.max(0.1, len - gap);
    const seg = '<circle class="seg" r="15.9155" cx="21" cy="21" fill="none" stroke="' + col + '" stroke-width="6" stroke-dasharray="' + dl + ' ' + (C - dl) + '" stroke-dashoffset="' + (25 - off) + '"><title>' + esc(it.label) + ': ' + fmtN(it.n) + ' (' + pct + '%)</title></circle>';
    const href = hrefFn ? hrefFn(it) : '';
    segs += href ? '<a href="' + esc(href) + '">' + seg + '</a>' : seg;
    off += len;
  });
  const legend = items.map(it => {
    const pct = Math.round(it.n / total * 1000) / 10;
    const inner = '<i class="dot" style="background:' + it.color + '"></i><span class="ll">' + esc(it.label) + '</span><span class="ln">' + fmtN(it.n) + ' <em>' + pct + '%</em></span>';
    const href = hrefFn ? hrefFn(it) : '';
    return href ? '<a class="li" href="' + esc(href) + '">' + inner + '</a>' : '<div class="li">' + inner + '</div>';
  }).join('');
  return '<div class="donutwrap"><svg viewBox="0 0 42 42" class="donut" role="img" aria-label="Deals by stage">' + segs + '<text x="21" y="20.5" text-anchor="middle" class="dn2">' + fmtN(total) + '</text><text x="21" y="26" text-anchor="middle" class="dl">Deals</text></svg><div class="legend">' + legend + '</div></div>';
}

function uniStats() {
  const map = {};
  rows.forEach(r => {
    const u = uniOf(r), s = map[u] || (map[u] = {name: u, n: 0, active: 0, stale: 0, month: 0});
    s.n++;
    if (!isClosed(r)) s.active++;
    if (cmSrc && isStale(r)) s.stale++;
  });
  const dk = createdKey(), cm = lastMonths(1)[0];
  if (dk) rows.forEach(r => { if (monthOf(r, dk) === cm) map[uniOf(r)].month++; });
  return map;
}

const uSel = new Set();
function renderUBar() {
  const b = $('uSelBar'); if (!b) return;
  b.hidden = !uSel.size;
  if (!uSel.size) return;
  const st = uniStats(); let n = 0; uSel.forEach(u => { n += st[u] ? st[u].n : 0; });
  b.innerHTML = '<button type="button" class="uopen">Open ' + uSel.size + ' selected · ' + fmtN(n) + ' deals</button><button type="button" class="ghost uclr">Clear</button>';
}
$('unav').addEventListener('change', e => { const c = e.target.closest('.uck'); if (!c) return; if (c.checked) uSel.add(c.dataset.n); else uSel.delete(c.dataset.n); renderUBar(); });
$('unav').addEventListener('click', e => {
  if (e.target.closest('.uopen')) { location.hash = hashFor(Array.from(uSel).join(MS), null, false); return; }
  if (e.target.closest('.uclr')) { uSel.clear(); $('unav').querySelectorAll('.uck').forEach(c => { c.checked = false; }); renderUBar(); }
});
/* Priority leads: the four "needs attention" lists. A lead can be in more than one list, so "any" counts each lead once. */
function prioStats() {
  const o = {stale: [], nc: [], pay: [], doc: []}, any = new Set();
  rows.forEach(r => {
    if (cmSrc && isStale(r)) { o.stale.push(r.ID); any.add(r.ID); }
    if (cmSrc && isNotContacted(r)) { o.nc.push(r.ID); any.add(r.ID); }
    if (passF(r, 'paydue')) { o.pay.push(r.ID); any.add(r.ID); }
    if (passF(r, 'docpend')) { o.doc.push(r.ID); any.add(r.ID); }
  });
  return {stale: o.stale.length, nc: o.nc.length, pay: o.pay.length, doc: o.doc.length, any: any.size};
}
function renderSide() {
  const st = uniStats();
  const names = Object.keys(st).sort((a, b) => a === UNASSIGNED ? 1 : b === UNASSIGNED ? -1 : a.localeCompare(b, undefined, {numeric: true, sensitivity: 'base'}));
  if (route.type === 'uni' && route.name.indexOf(MS) >= 0) { uSel.clear(); route.name.split(MS).forEach(n => uSel.add(n)); }
  const curU = route.type === 'uni' ? route.name.split(MS) : [];
  $('unav').innerHTML = '<div id="uSelBar" class="usel" hidden></div>' + names.map(n => '<div class="urow"><input type="checkbox" class="uck" data-n="' + esc(n) + '" aria-label="Select ' + esc(n) + '"' + (uSel.has(n) ? ' checked' : '') + '><a href="' + hashFor(n, null, false) + '" class="' + (curU.indexOf(n) >= 0 ? 'on' : '') + '"><b title="' + esc(n) + '">' + esc(n) + '</b><span>' + fmtN(st[n].n) + '</span></a></div>').join('');
  renderUBar();
  { const ps = prioStats(); $('navPrio').textContent = ps.any ? fmtN(ps.any) : ''; $('navP').title = 'Priority leads · ' + fmtN(ps.any) + ' need attention'; }
  paintViews(); paintWaMode();
  const cur = route.type === 'home' ? 'home' : route.sheet ? ((route.f === 'stale' || route.f === 'notcontacted' || route.f === 'paydue' || route.f === 'docpend') ? 'prio' : (route.type === 'master' && !route.f && route.p === null && route.m === null && route.s === null && !route.q ? 'all' : '')) : (route.type === 'master' ? 'master' : '');
  $('nav').querySelectorAll('a').forEach(a => a.classList.toggle('on', a.dataset.nav === cur));
  $('navP').classList.toggle('on', cur === 'prio');
  renderRepNav();
}

function tile(label, icon, color, value, sub, href, sp, cls) {
  return '<a class="tile ' + (cls || '') + '" style="--c:' + color + '" href="' + esc(href || '#/') + '"><span class="tl"><i>' + icon + '</i>' + esc(label) + '</span><b>' + value + '</b><span class="ts">' + sub + '</span>' + (sp || '') + '</a>';
}

function renderHome() {
  const st = uniStats(), total = rows.length;
  const names = Object.keys(st);
  const active = rows.filter(r => !isClosed(r)).length;
  const stale = cmSrc ? rows.filter(isStale).length : 0;
  $('masterNum').textContent = 'All universities together · ' + fmtN(total) + ' deals';
  $('heroStats').innerHTML =
    '<div><i>🏛</i><span><b>' + fmtN(names.filter(n => n !== UNASSIGNED).length) + '</b><span>Universities</span></span></div>' +
    '<div><i>🤝</i><span><b>' + fmtN(total) + '</b><span>Total deals</span></span></div>' +
    '<div><i>⏳</i><span><b>' + fmtN(active) + '</b><span>Active deals</span></span></div>';
  // KPI tiles
  const mc = monthlyCounts(rows, 8), thisM = mc[mc.length - 1].n, lastM = mc[mc.length - 2].n;
  let chg = '';
  if (lastM > 0) { const p = Math.round((thisM - lastM) / lastM * 100); chg = ' <span class="' + (p >= 0 ? 'up' : 'dn') + '">' + (p >= 0 ? '▲' : '▼') + ' ' + Math.abs(p) + '%</span> vs last month'; }
  else if (createdKey()) chg = ' (' + fmtN(lastM) + ' last month)';
  const nUni = names.filter(n => n !== UNASSIGNED).length, pk = progKey();
  const nProg = pk ? new Set(rows.map(r => fv(r, pk)).filter(v => v && v !== NONE && v !== UNASSIGNED)).size : 0;
  let k = tile('Total deals', '📊', 'var(--s1)', fmtN(total), createdKey() ? fmtN(thisM) + ' new this month' + (lastM > 0 ? ' <span class="' + (thisM >= lastM ? 'up' : 'dn') + '">' + (thisM >= lastM ? '▲' : '▼') + ' ' + Math.abs(Math.round((thisM - lastM) / lastM * 100)) + '%</span>' : '') : 'All deals', '#/master/sheet', spark(mc.map(m => m.n), 'var(--s1)'));
  k += tile('Universities', '🏛', 'var(--s7)', fmtN(nUni), 'With at least one deal', '#/master');
  k += tile('Active deals', '⏳', 'var(--s3)', fmtN(active), total ? Math.round(active / total * 100) + '% of all deals' : '', '#/master/sheet?f=active');
  if (cmSrc) k += tile('Follow-up overdue', '⚑', '#e03131', fmtN(stale), 'No comment in ' + STALE_DAYS + '+ days', '#/master/sheet?f=stale', '', 'warn');
  if (cmSrc) k += tile('Not contacted', '📵', '#e8590c', fmtN(rows.filter(isNotContacted).length), 'Active, no comment yet', '#/master/sheet?f=notcontacted', '', 'warn');
  if (pk) k += tile('Programs', '📚', 'var(--s5)', fmtN(nProg), 'Across all universities', '#/master');
  $('hKpis').innerHTML = k;
  // payment mode + exits
  const mk = modeKey(), pc = n => total ? (Math.round(n / total * 1000) / 10) + '% of all deals' : '';
  let k2 = '';
  if (createdKey()) k2 += tile('New this month', '✨', 'var(--s2)', fmtN(thisM), 'Created this month', hashFor(null, {x: [['_MON', lastMonths(1)[0]]]}, true), spark(mc.map(m => m.n), 'var(--s2)'));
  if (mk) {
    const modeVals = Array.from(new Set(rows.map(r => cellVal(r, mk))));
    [['Semester payment', '📘', /sem/i, 'var(--s1)'], ['Annual payment', '📅', /annual|year/i, 'var(--s3)'], ['Lumpsum payment', '💰', /lump/i, 'var(--s4)']].forEach(m => {
      const vs = modeVals.filter(v => m[2].test(v));
      const n = rows.filter(r => vs.indexOf(cellVal(r, mk)) >= 0).length;
      k2 += tile(m[0], m[1], m[3], fmtN(n), vs.length ? pc(n) + ' · click to open' : 'No such mode in your data', vs.length ? hashFor(null, {x: [[mk, vs.join(MS)]]}, true) : '#/');
    });
  }
  [['Dropped', '🚪', /\bdropped\b/i, '#e8590c'], ['Refund', '💸', /refund/i, '#e03131']].forEach(m => {
    const sv = Array.from(new Set(rows.map(r => r.STAGE_ID || ''))).filter(v => v && m[2].test(v));
    const n = rows.filter(r => sv.indexOf(r.STAGE_ID || '') >= 0).length;
    k2 += tile(m[0], m[1], m[3], fmtN(n), sv.length ? pc(n) + ' · stage: ' + esc(sv.join(', ')) : 'No such stage in your data', sv.length ? hashFor(null, {x: [['STAGE_ID', sv.join(MS)]]}, true) : '#/', '', sv.length ? 'warn' : '');
  });
  $('hKpis2').innerHTML = k2;
  // charts
  renderTopChart(st);
  renderStatusChart();
  renderTrendChart();
  renderProgChart();
  renderQuick();
  loadRecent();
  // universities
  const q = ($('uq').value || '').toLowerCase().trim();
  let list = names.filter(n => n.toLowerCase().indexOf(q) >= 0);
  const sortBy = $('uSort').value;
  list.sort((a, b) => {
    if (sortBy === 'name') return a === UNASSIGNED ? 1 : b === UNASSIGNED ? -1 : a.localeCompare(b, undefined, {numeric: true, sensitivity: 'base'});
    if (sortBy === 'dealsAsc') return st[a].n - st[b].n || a.localeCompare(b);
    if (sortBy === 'stale') return st[b].stale - st[a].stale || st[b].n - st[a].n;
    return st[b].n - st[a].n || a.localeCompare(b);
  });
  const maxN = Math.max.apply(null, names.map(n => st[n].n).concat([1]));
  $('uniGrid').innerHTML = list.map(n => {
    const s = st[n], pct = total ? Math.round(s.n / total * 1000) / 10 : 0;
    return '<a class="ucard" style="--c:' + hashColor(n) + '" href="' + hashFor(n, null, false) + '">' +
      '<div class="uh"><span class="av">' + esc(initials(n)) + '</span><div class="un"><b title="' + esc(n) + '">' + esc(n) + '</b><small>' + fmtN(s.active) + ' active' + (s.stale ? ' · <span class="od">' + fmtN(s.stale) + ' overdue</span>' : '') + '</small></div></div>' +
      '<div class="nm"><b>' + fmtN(s.n) + ' deals</b>' + (s.month ? '<span>+' + fmtN(s.month) + ' this month</span>' : '') + '</div>' +
      '<div class="pb"><i style="width:' + Math.max(2, s.n / maxN * 100) + '%"></i></div><div class="pt"><span>' + pct + '% of total</span></div></a>';
  }).join('');
  const he = $('homeEmpty');
  he.hidden = list.length > 0;
  he.textContent = rows.length ? 'No university matches.' : 'No data yet. Press "Refresh data".';
}

function renderTopChart(st) {
  const n = +$('selTop').value;
  let names = Object.keys(st).sort((a, b) => st[b].n - st[a].n || a.localeCompare(b));
  if (n > 0) names = names.slice(0, n);
  if (!names.length) { $('chTop').innerHTML = '<div class="empty">No data yet.</div>'; return; }
  const mx = Math.max.apply(null, names.map(u => st[u].n).concat([1]));
  $('chTop').innerHTML = '<div class="rank">' + names.map((u, i) => '<a class="rk" style="--c:' + hashColor(u) + '" href="' + hashFor(u, null, true) + '"><span class="n">' + (i + 1) + '</span><span class="av">' + esc(initials(u)) + '</span><span class="nm" title="' + esc(u) + '">' + esc(u) + '</span><span class="ct">' + fmtN(st[u].n) + ' deals</span><span class="bar"><i style="width:' + Math.max(3, st[u].n / mx * 100) + '%"></i></span></a>').join('') + '</div>';
}
function renderProgChart() {
  const pk = progKey(), box = $('chProg');
  if (!pk) { box.innerHTML = '<div class="empty">No program field found.</div>'; return; }
  const t = tally(rows, r => fv(r, pk));
  let names = Object.keys(t).filter(v => v !== NONE && v !== UNASSIGNED).sort((a, b) => t[b] - t[a] || a.localeCompare(b));
  if (!names.length) { box.innerHTML = '<div class="empty">No data yet.</div>'; return; }
  const top = names.slice(0, 5), rest = names.slice(5), restN = rest.reduce((a, v) => a + t[v], 0);
  const bars = top.map(v => ({l: v, n: t[v], href: hashFor(null, {p: v}, true)}));
  if (restN) bars.push({l: 'Others', n: restN, href: hashFor(null, {x: [[pk, rest.join(MS)]]}, true)});
  const mx = Math.max.apply(null, bars.map(b => b.n).concat([1]));
  box.innerHTML = '<div class="vb">' + bars.map((b, i) => '<a class="vbc" style="--c:' + hashColor(b.l) + ';--h:' + Math.max(3, b.n / mx * 100).toFixed(1) + ';--i:' + i + '" href="' + b.href + '" title="' + esc(b.l) + '"><b>' + fmtN(b.n) + '</b><i></i><span>' + esc(b.l) + '</span></a>').join('') + '</div>';
}
function renderQuick() {
  const stale = cmSrc ? rows.filter(isStale).length : 0;
  const q = [['⏰', '#e03131', 'Follow-up overdue', cmSrc ? fmtN(stale) + ' to chase' : 'Open the list', '#/master/sheet?f=stale'],
    ['📋', '#21bbb0', 'All deals', fmtN(rows.length) + ' in the table', '#/master/sheet'],
    ['📄', '#e8590c', 'Documents pending', 'See who is missing', '#/reports/docs'],
    ['📈', '#0b8a4f', 'Re Reg Percentage', 'Done vs pending', '#/reports/rr']];
  $('chQuick').innerHTML = q.map(x => '<a class="qb" style="--c:' + x[1] + '" href="' + x[4] + '"><i>' + x[0] + '</i><span><b>' + x[2] + '</b><small>' + x[3] + '</small></span></a>').join('');
}
let recentAt = 0, recentItems = null;
function ago(iso) {
  const d = new Date(iso); if (isNaN(d)) return '';
  const m = Math.round((Date.now() - d) / 60000);
  if (m < 1) return 'just now'; if (m < 60) return m + ' min ago';
  const h = Math.round(m / 60); if (h < 24) return h + ' h ago';
  const dd = Math.round(h / 24); return dd < 30 ? dd + ' d ago' : actWhen(iso).slice(0, 10);
}
function drawRecent() {
  const box = $('chAct'); if (!box) return;
  const byId = {}; allRows.forEach(r => { byId[r.ID] = r; });
  const items = (recentItems || []).filter(e => !e.type).slice(0, 5);
  if (!items.length) { box.innerHTML = '<div class="empty">No changes saved yet. Edits made here show up in this list.</div>'; return; }
  box.innerHTML = '<div class="ra">' + items.map((e, i) => {
    const r = byId[e.deal], nm = r ? (r._NAME || r.TITLE || ('Deal ' + e.deal)) : ('Deal #' + e.deal);
    const ch = Object.keys(e.changes || {}).map(k => e.changes[k]).filter(c => !/comment history/i.test(c.field));
    const what = e.comment ? '💬 ' + e.comment : ch.length ? (ch[0].field + ' → ' + (ch[0].new === '' ? '(empty)' : ch[0].new)) : 'Updated';
    return '<button type="button" class="ri" data-id="' + esc(e.deal) + '" style="--c:' + actColor(e.user || '?') + ';animation-delay:' + (.45 + i * .07) + 's"><i class="ic">' + (avImg(e.user) || (e.comment ? '💬' : '✏️')) + '</i><span><b>' + esc(nm) + '</b><small>' + esc((e.user || '') + ' · ' + what) + '</small></span><time>' + esc(ago(e.at)) + '</time></button>';
  }).join('') + '</div>';
  box.querySelectorAll('button.ri').forEach(b => { b.onclick = () => { const r = allRows.find(x => x.ID === b.dataset.id); if (r) openEdit(r); }; });
}
async function loadRecent() {
  if (recentItems) drawRecent();
  if (Date.now() - recentAt < 20000 && recentItems) return;
  recentAt = Date.now();
  try {
    const r = await fetch('api.php?action=activity&limit=30', {credentials: 'same-origin'});
    if (r.status === 401) { location.reload(); return; }
    const j = await r.json();
    if (j && j.ok) { mergeAV(j); recentItems = j.items || []; drawRecent(); }
  } catch (e) { if (!recentItems && $('chAct')) $('chAct').innerHTML = '<div class="empty">Could not load recent activity.</div>'; }
}
function renderStatusChart() {
  const t = tally(rows, r => r.STAGE_ID || '(no stage)');
  const order = stagesList.concat(Object.keys(t).filter(s => stagesList.indexOf(s) < 0)).filter(s => t[s]);
  if (!order.length) { $('chStatus').innerHTML = '<div class="empty">No data yet.</div>'; return; }
  const items = order.slice(0, SERIES).map(s => ({label: s, n: t[s], key: s}));
  const rest = order.slice(SERIES).reduce((a, s) => a + t[s], 0);
  if (rest) items.push({label: 'Other stages', n: rest, other: true, key: order.slice(SERIES).join(MS)});
  $('chStatus').innerHTML = donutHtml(items, rows.length, it => hashFor(null, {s: it.key}, true));
}
function renderTrendChart() {
  SVG_W = 640;
  const ln = lineHtml(rows, +$('selMonths').value);
  SVG_W = 640;
  $('chTrend').innerHTML = ln || '<div class="empty">Not enough dated deals yet.</div>';
}

/* global search: centered pop-up */
(function () {
  const ov = $('gqOv'), inp = $('gqIn'), res = $('gqRes'), top = $('gsearch');
  const RK = 'pranav_recent_searches';
  let act = 0, uniCnt = [], ROWS = [];
  const rs = () => { try { const a = JSON.parse(localStorage.getItem(RK) || '[]'); return Array.isArray(a) ? a : []; } catch (e) { return []; } };
  const rsAdd = q => { q = String(q || '').trim(); if (q.length < 2) return; track('search', q); try { const a = rs().filter(x => x.toLowerCase() !== q.toLowerCase()); a.unshift(q); localStorage.setItem(RK, JSON.stringify(a.slice(0, 6))); } catch (e) {} };
  const hl = (t, terms) => {
    t = String(t == null ? '' : t); if (!terms.length) return esc(t);
    const re = new RegExp('(' + terms.map(x => x.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')).join('|') + ')', 'ig');
    return t.split(re).map((p, i) => i % 2 ? '<mark>' + esc(p) + '</mark>' : esc(p)).join('');
  };
  const pages = () => {
    const p = [{t: 'Home', k: 'dashboard overview', h: '#/', i: '🏠'}, {t: 'Master table', k: 'all deals sheet list', h: '#/master/sheet', i: '📋'}, {t: 'All reports', k: 'reports', h: '#/reports', i: '📊'}];
    if (typeof REPORTS !== 'undefined') REPORTS.forEach(r => p.push({t: r.title, k: 'report', h: '#/reports/' + r.id, i: r.icon || '📊'}));
    return p;
  };
  const dealHtml = (r, terms) => {
    const nm = r._NAME || r.TITLE || ('Deal ' + r.ID), parts = [uniOf(r), r.STAGE_ID, r._PHONE].filter(Boolean), shown = (nm + ' ' + parts.join(' ')).toLowerCase();
    if (terms.length && !terms.every(t => shown.indexOf(t) >= 0)) {
      const miss = terms.filter(t => shown.indexOf(t) < 0);
      const k = Object.keys(r).find(x => x.charAt(0) !== '_' && r[x] != null && typeof r[x] !== 'object' && miss.every(t => String(r[x]).toLowerCase().indexOf(t) >= 0));
      if (k) { let v = String(r[k]).replace(/\s+/g, ' ').trim(), i = v.toLowerCase().indexOf(miss[0]); if (v.length > 48) { const st = Math.max(0, i - 14); v = (st ? '…' : '') + v.slice(st, st + 48) + '…'; } parts.push('“' + v + '”'); }
    }
    return '<button type="button" class="gqi" data-k="deal" data-id="' + esc(r.ID) + '"><i class="ic">👤</i><span><b>' + hl(nm, terms) + '</b><small>' + hl(parts.join(' · '), terms) + '</small></span></button>';
  };
  function prep() {
    ROWS = (typeof allRows !== 'undefined' && allRows && allRows.length) ? allRows : rows;
    const m = new Map(); ROWS.forEach(r => { const u = uniOf(r); if (u !== UNASSIGNED) m.set(u, (m.get(u) || 0) + 1); });
    uniCnt = Array.from(m.entries()).sort((a, b) => b[1] - a[1]);
  }
  function paintEmpty() {
    let h = '';
    const rec = rs();
    if (rec.length) h += '<div class="gqh">Recent searches</div><div class="gqch">' + rec.map(q => '<button type="button" class="gqi" data-k="chip" data-v="' + esc(q) + '">🕘 ' + esc(q) + '</button>').join('') + '</div>';
    if (uniCnt.length) h += '<div class="gqh">Universities</div><div class="gqch">' + uniCnt.slice(0, 6).map(u => '<button type="button" class="gqi" data-k="uni" data-v="' + esc(u[0]) + '">🎓 ' + esc(u[0]) + ' <em>' + fmtN(u[1]) + '</em></button>').join('') + '</div>';
    const ri = (recentItems || []).filter(e => !e.type).slice(0, 5), by = {}; ROWS.forEach(r => { by[r.ID] = r; });
    const seen = {}, rd = ri.map(e => by[e.deal]).filter(r => r && !seen[r.ID] && (seen[r.ID] = 1));
    if (rd.length) h += '<div class="gqh">Recently edited</div>' + rd.map(r => dealHtml(r, [])).join('');
    h += '<div class="gqh">Jump to</div>' + pages().map(p => '<button type="button" class="gqi" data-k="nav" data-h="' + esc(p.h) + '"><i class="ic">' + p.i + '</i><span><b>' + esc(p.t) + '</b></span></button>').join('');
    h += '<div class="gqh">Tip</div><div class="gqnone" style="padding:6px 12px 14px;text-align:left">Type a name, phone number, email, university, stage or anything in a deal. Several words narrow it down.</div>';
    res.innerHTML = h;
  }
  function paint() {
    const v = inp.value.trim();
    if (!v) { paintEmpty(); mark(0); return; }
    const terms = v.toLowerCase().split(/\s+/).filter(Boolean), lq = v.toLowerCase();
    const sc = r => { const n = String(r._NAME || r.TITLE || '').toLowerCase(); return n.indexOf(lq) === 0 ? 0 : n.indexOf(lq) >= 0 ? 1 : terms.every(t => n.indexOf(t) >= 0) ? 2 : 3; };
    const hits = ROWS.filter(r => terms.every(t => (r._s || '').indexOf(t) >= 0)).map(r => [sc(r), r]).sort((a, b) => a[0] - b[0]).map(x => x[1]);
    const us = uniCnt.filter(u => terms.every(t => u[0].toLowerCase().indexOf(t) >= 0)).slice(0, 4);
    const pg = pages().filter(p => terms.every(t => (p.t + ' ' + p.k).toLowerCase().indexOf(t) >= 0)).slice(0, 4);
    let h = '';
    if (pg.length) h += '<div class="gqh">Pages and reports</div>' + pg.map(p => '<button type="button" class="gqi" data-k="nav" data-h="' + esc(p.h) + '"><i class="ic">' + p.i + '</i><span><b>' + hl(p.t, terms) + '</b></span></button>').join('');
    if (us.length) h += '<div class="gqh">Universities</div>' + us.map(u => '<button type="button" class="gqi" data-k="uni" data-v="' + esc(u[0]) + '"><i class="ic">🎓</i><span><b>' + hl(u[0], terms) + '</b></span><em>' + fmtN(u[1]) + ' deals</em></button>').join('');
    if (hits.length) h += '<div class="gqh">Students · ' + fmtN(hits.length) + ' match' + (hits.length > 1 ? 'es' : '') + '</div>' + hits.slice(0, 50).map(r => dealHtml(r, terms)).join('') +
      '<a class="gqall" href="' + esc(hashFor(null, {q: v}, true)) + '" data-k="all">' + (hits.length > 50 ? 'See all ' + fmtN(hits.length) + ' in the table →' : 'Open these in the table →') + '</a>';
    if (!h) h = '<div class="gqnone">No match for “' + esc(v) + '”<br><small>Try fewer words, or part of a name or phone number.</small></div>';
    res.innerHTML = h; mark(0); res.scrollTop = 0;
  }
  const items = () => Array.from(res.querySelectorAll('.gqi'));
  function mark(i, ns) { const l = items(); if (!l.length) { act = -1; return; } act = Math.max(0, Math.min(l.length - 1, i)); l.forEach((b, j) => b.classList.toggle('on', j === act)); if (!ns) l[act].scrollIntoView({block: 'nearest'}); }
  function open(prefill) {
    prep(); ov.hidden = false; inp.value = prefill || ''; paint(); inp.focus();
    if (!recentItems && typeof loadRecent === 'function') loadRecent().then(() => { if (!ov.hidden && !inp.value) paint(); });
  }
  function close() { ov.hidden = true; try { top.blur(); } catch (e) {} }
  function go(b) {
    if (!b) return; const k = b.dataset.k, q = inp.value.trim();
    if (k === 'chip') { inp.value = b.dataset.v; paint(); inp.focus(); return; }
    rsAdd(q); close();
    if (k === 'deal') { const r = ROWS.find(x => x.ID === b.dataset.id); if (r) openEdit(r); }
    else if (k === 'uni') location.hash = hashFor(b.dataset.v, null, false);
    else if (k === 'nav') location.hash = b.dataset.h;
  }
  top.addEventListener('focus', () => open(''));
  top.addEventListener('click', () => { if (ov.hidden) open(''); });
  top.addEventListener('keydown', e => { if (e.key.length === 1 && !e.ctrlKey && !e.metaKey && ov.hidden) open(e.key); });
  inp.addEventListener('input', paint);
  inp.addEventListener('keydown', e => {
    if (e.key === 'Escape') { e.preventDefault(); e.stopPropagation(); close(); return; }
    if (e.key === 'ArrowDown') { e.preventDefault(); mark(act + 1); return; }
    if (e.key === 'ArrowUp') { e.preventDefault(); mark(act - 1); return; }
    if (e.key === 'Enter') {
      e.preventDefault(); const v = inp.value.trim();
      if (e.shiftKey && v) { rsAdd(v); close(); location.hash = hashFor(null, {q: v}, true); return; }
      const l = items(); if (l[act]) go(l[act]); else if (v) { rsAdd(v); close(); location.hash = hashFor(null, {q: v}, true); }
    }
  });
  res.addEventListener('click', e => { const a = e.target.closest('a.gqall'); if (a) { rsAdd(inp.value); close(); return; } const b = e.target.closest('.gqi'); if (b) go(b); });
  res.addEventListener('mousemove', e => { const b = e.target.closest('.gqi'); if (b) { const l = items(), i = l.indexOf(b); if (i >= 0 && i !== act) mark(i, 1); } });
  ov.addEventListener('mousedown', e => { if (e.target === ov) close(); });
})();

const SB_KEY = 'pranav_sb_collapsed', UN_KEY = 'pranav_unis_open';
const lsg = k => { try { return localStorage.getItem(k) === '1'; } catch (e) { return false; } };
const lss = (k, v) => { try { localStorage.setItem(k, v ? '1' : '0'); } catch (e) {} };
const narrow = () => window.matchMedia('(max-width:900px)').matches;
const PIN_KEY = 'pranav_sb_pin';
$('shell').classList.toggle('col', narrow() ? lsg(SB_KEY) : !lsg(PIN_KEY));
$('shell').classList.toggle('pin', !narrow() && lsg(PIN_KEY));
function setUnis(open) { $('unav').hidden = !open; $('navU').setAttribute('aria-expanded', open ? 'true' : 'false'); lss(UN_KEY, open); }
const inRail = () => $('shell').classList.contains('col') && !narrow() && !$('shell').classList.contains('nosb');
function expandRail() { $('shell').classList.remove('col'); lss(SB_KEY, false); }
$('navU').onclick = () => { if (inRail()) { expandRail(); setUnis(true); return; } setUnis($('unav').hidden); };
setUnis(lsg(UN_KEY));
let sbPin = !narrow() && lsg(PIN_KEY), sbT = 0;
const floatOn = () => !narrow() && !$('shell').classList.contains('nosb');
function sbOpen(pin) { clearTimeout(sbT); sbPin = !!pin; $('shell').classList.remove('col'); $('shell').classList.toggle('pin', sbPin); if (pin) lss(PIN_KEY, true); }
function sbClose() { clearTimeout(sbT); sbPin = false; $('shell').classList.remove('pin'); lss(PIN_KEY, false); if (floatOn()) $('shell').classList.add('col'); }
function toggleSide() {
  if (!floatOn()) { document.body.classList.toggle('sbopen'); return; }
  if (sbPin) sbClose(); else sbOpen(true);
}
$('side').addEventListener('mouseenter', () => { if (!floatOn() || !$('shell').classList.contains('col')) return; clearTimeout(sbT); sbT = setTimeout(() => sbOpen(false), 200); });
$('side').addEventListener('mouseleave', () => { if (!floatOn()) return; clearTimeout(sbT); if (!sbPin) sbT = setTimeout(sbClose, 350); });
document.addEventListener('click', e => { if (floatOn() && !sbPin && !$('shell').classList.contains('col') && !e.target.closest('#side')) sbClose(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape' && floatOn() && !sbPin && !$('shell').classList.contains('col')) sbClose(); });
addEventListener('resize', () => { if (floatOn() && !sbPin) $('shell').classList.add('col'); });
(function () {
  const TH = [1030, 1000, 960, 850, 640, 480], mn = document.querySelector('.main');
  function fit() { const w = mn.getBoundingClientRect().width; TH.forEach(t => document.body.classList.toggle('mw-' + t, w <= t)); }
  if (window.ResizeObserver) new ResizeObserver(fit).observe(mn); else addEventListener('resize', fit);
  fit();
})();
$('logoBtn').onclick = e => { e.preventDefault(); e.stopPropagation(); toggleSide(); };
$('logoBtn').onkeydown = e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleSide(); } };
$('btnMenu').onclick = () => document.body.classList.toggle('sbopen');
$('sbback').onclick = () => document.body.classList.remove('sbopen');
$('side').addEventListener('click', e => { if (e.target.closest('a')) { document.body.classList.remove('sbopen'); if (floatOn() && !sbPin) setTimeout(sbClose, 120); } });
$('selTop').onchange = () => renderTopChart(uniStats());
$('selMonths').onchange = renderTrendChart;
$('uSort').onchange = () => { if (route.type === 'home') renderHome(); };
$('vGrid').onclick = () => { $('uniGrid').classList.remove('list'); $('vGrid').classList.add('on'); $('vList').classList.remove('on'); };
$('vList').onclick = () => { $('uniGrid').classList.add('list'); $('vList').classList.add('on'); $('vGrid').classList.remove('on'); };


/* ---------- REPORTS ---------- */
/* ---------- DOCUMENTS PENDING ---------- */
/* ---- Re Reg Percentage ---- */
const SG_KEY = 'pranav_stage_groups';
const GRP = ['Active (re-registered)', 'Completed', 'Refund', 'Dropped / lost', 'Other / pending'];
const RR = {R: 'Done', O: 'Pending', C: 'Completed', N: 'Not counted'};
const todayStr = () => { const d = new Date(), p = n => String(n).padStart(2, '0'); return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate()); };
function payDates(r) { const l = colByRole('lastpay'), n = colByRole('nextpay'); return [l ? String(r[l.key] || '').slice(0, 10) : '', n ? String(r[n.key] || '').slice(0, 10) : '']; }
function sgLoad() { try { return JSON.parse(localStorage.getItem(SG_KEY) || '{}') || {}; } catch (e) { return {}; } }
function sgSave(o) { try { localStorage.setItem(SG_KEY, JSON.stringify(o)); } catch (e) {} }
function sgAuto(s) {
  if (/refund/i.test(s)) return GRP[2];
  if (/dropp|drop\b|lost|cancel|reject|not interested|junk|withdr|discontinu|dead/i.test(s)) return GRP[3];
  if (/complet|won\b|graduat|passed out|alumni/i.test(s)) return GRP[1];
  if (/pend|due|unpaid|not paid|on hold|hold\b|follow/i.test(s)) return GRP[4];
  return GRP[0];
}
function stageGroup(s) { const o = sgLoad(); return o[s] && GRP.indexOf(o[s]) >= 0 ? o[s] : sgAuto(s); }
function semOf(r) { const k = semKeyCur(); const m = k && /(\d+)/.exec(String(r[k] || '')); return m ? +m[1] : 0; }
function rrStatus(r) {
  const g = stageGroup(r.STAGE_ID || '');
  if (g === GRP[1]) return RR.C;
  if (g !== GRP[0]) return RR.N;
  const d = payDates(r), t = todayStr();
  if (!d[0]) return RR.N;            // no Last Payment Date: not counted at all
  if (!d[1]) return RR.O;            // has a last payment but no next payment date: delayed
  if (t > d[1]) return RR.O;         // next payment date has passed: delayed
  if (t >= d[0]) return RR.R;        // between last and next payment date: done
  return RR.O;                       // anything else: delayed
}
function semStatusKey(n) { const c = cols.find(c => SEMRE.test(c.label) && semNum(c) === n); return c ? c.key : null; }
function rrPay(r) {
  const k = semStatusKey(semOf(r));
  if (!k) return 'Unknown';
  const v = String(r[k] || '').trim().toLowerCase();
  if (!v) return 'Unknown';
  if (/not\s*paid|unpaid|pending|due|^no$|^n$|false/.test(v)) return 'Not paid';
  if (/paid|^yes$|^y$|done|complete|true/.test(v)) return 'Paid';
  return 'Unknown';
}
function rrFv(r, k) {
  if (k === '_RR' || k === '_RRA') return rrStatus(r);
  if (k === '_RRPAY') return rrPay(r);
  if (k === '_ALUM') return stageGroup(r.STAGE_ID || '') === GRP[1] ? 'Alumni' : 'Not alumni';
  if (k === '_SEMN') { const n = semOf(r); return n ? 'Sem ' + n : 'Unknown'; }
  return null;
}
function renderRR(def) {
  const rq = route.rq || {}, defs = fieldDefs();
  const semK = semKeyCur();
  const lpC = colByRole('lastpay'), npC = colByRole('nextpay');
  const view = Object.keys(RR).map(k => RR[k]).indexOf(rq.view) >= 0 ? rq.view : '';
  const by = defs.find(f => f.id === rq.by) || defs[0];
  const ST = '_RR';
  $('rpBuilder').hidden = false;
  $('rpBuilder').innerHTML = '<div class="rpt">View</div>' +
    selHtml('view', 'Quick view', [RR.R, RR.O, RR.C, RR.N].map(v => ({v: v, t: v})), view, 'Everyone') +
    selHtml('by', 'Break the table down by', defs.map(f => ({v: f.id, t: f.label})), rq.by && defs.some(f => f.id === rq.by) ? rq.by : '', by ? by.label : '—');
  $('rpFilters').innerHTML = '<div class="rpt">Filters</div>' + defs.map(f => {
    const vals = new Set(); rows.forEach(r => vals.add(fv(r, f.key)));
    return selHtml(f.id, f.label, orderVals(f.key, vals).map(v => ({v: v, t: v})), rq[f.id] || '', 'All', true);
  }).join('');
  enhanceSelects();
  const flt = repFilters();
  $('rpReset').disabled = !flt.length && !view;
  rpCache = null;
  if (!lpC || !npC) {
    $('rpSummary').textContent = '';
    $('rpBody').innerHTML = '<div class="empty">I could not find the “Last Payment Date (P)” and “Next Payment Date (P)” fields in your data. Press “Refresh data”.</div>';
    return;
  }
  const base = flt.map(f => [f.key, f.val]);
  const all = rows.filter(r => base.every(p => xMatch(r, p)));
  const list = view ? all.filter(r => rrStatus(r) === view) : all;
  const rb = location.hash;
  const lk = extra => hashFor(null, {x: base.concat(extra), rb: rb}, true);
  const pct = (a, b) => b ? Math.round(a / b * 1000) / 10 : 0;
  const cnt = {}; Object.keys(RR).forEach(k => { cnt[RR[k]] = 0; });
  all.forEach(r => { cnt[rrStatus(r)]++; });
  const cohort = cnt[RR.R] + cnt[RR.O];
  $('rpSummary').textContent = fmtN(all.length) + ' students · ' + fmtN(cohort) + ' active students counted · click any number to open those students';
  if (!all.length) { $('rpBody').innerHTML = '<div class="empty">No deals match these filters.</div>'; return; }
  const P = (s) => [[ST, s]];
  let h = '<div class="rpnote"><b>How this is worked out.</b> Only students whose stage is grouped as <b>Active</b> are counted, and only from the dates <b>Last Payment Date (P)</b> and <b>Next Payment Date (P)</b>. If <b>today</b> falls between the two dates (inclusive) the student is <b>Done</b>. <b>Everything else is Pending (delayed)</b>: no Next Payment Date, today later than the Next Payment Date, or a Last Payment Date still in the future. <b>Re-registration % = Done ÷ (Done + Pending).</b> Students with <b>no Last Payment Date</b> are shown under “Not counted” and are left out completely, and so are Completed students. Today is ' + esc(todayStr()) + '. Which stage counts as Active is set in the “Stage groups” box below.</div>';
  h += '<div class="hk">' +
    tile('Re-registration %', '📈', 'var(--s3)', pct(cnt[RR.R], cohort) + '%', fmtN(cnt[RR.R]) + ' of ' + fmtN(cohort) + ' active students', lk(P(RR.R))) +
    tile('Done', '✅', 'var(--s1)', fmtN(cnt[RR.R]), 'Today is between last and next payment date', lk(P(RR.R))) +
    tile('Pending', '⏳', '#e8590c', fmtN(cnt[RR.O]), pct(cnt[RR.O], cohort) + '% · next payment date has passed', lk(P(RR.O)), '', 'warn') + '</div>';
  h += '<div class="rpnote">Left out of the percentage: <a class="dn" href="' + esc(lk(P(RR.C))) + '">' + fmtN(cnt[RR.C]) + ' completed</a> · <a class="dn" href="' + esc(lk(P(RR.N))) + '">' + fmtN(cnt[RR.N]) + ' not counted</a> (not Active, or no Last Payment Date).</div>';
  // by semester
  const semRaw = {}, sg = {};
  all.forEach(r => { const n = semOf(r); if (n >= 1 && semK) { semRaw[n] = String(r[semK] || '').trim(); const s = rrStatus(r); const o = sg[n] || (sg[n] = {c: 0, r: 0}); if (s !== RR.C && s !== RR.N) { o.c++; if (s === RR.R) o.r++; } } });
  const sns = Object.keys(sg).map(Number).sort((a, b) => a - b);
  const smax = Math.max.apply(null, sns.map(n => sg[n].c).concat([1]));
  const c1 = sns.length ? sns.map(n => '<a class="hbar hb2" href="' + esc(lk([[ST, RR.R], [semK, semRaw[n]]])) + '"><span class="hl">Sem ' + n + '</span><span class="hb"><i class="li2" style="width:' + (sg[n].c / smax * 100) + '%"></i><i class="gi" style="width:' + (sg[n].r / smax * 100) + '%"></i></span><span class="hn">' + pct(sg[n].r, sg[n].c) + '%</span></a><div class="hsub">' + fmtN(sg[n].r) + ' done of ' + fmtN(sg[n].c) + ' active</div>').join('') : '<div class="empty">No active students with a semester filled in.</div>';
  // where everyone stands
  const smx = Math.max.apply(null, [RR.R, RR.O, RR.C, RR.N].map(k => cnt[k]).concat([1]));
  const colr = {}; colr[RR.R] = 'var(--s3)'; colr[RR.O] = '#e8590c'; colr[RR.C] = 'var(--s1)'; colr[RR.N] = 'var(--other)';
  const c2 = [RR.R, RR.O, RR.C, RR.N].map(k => '<a class="hbar" href="' + esc(lk(P(k))) + '"><span class="hl">' + esc(k) + '</span><span class="hb"><i style="width:' + Math.max(cnt[k] ? 3 : 0, cnt[k] / smx * 100) + '%;background:' + colr[k] + '"></i></span><span class="hn">' + fmtN(cnt[k]) + '</span></a>').join('');
  // stage groups
  const stCount = {}; all.forEach(r => { const s = r.STAGE_ID || '(no stage)'; stCount[s] = (stCount[s] || 0) + 1; });
  const stNames = orderVals('STAGE_ID', new Set(rows.map(r => r.STAGE_ID || '(no stage)')));
  const c3 = '<div class="sub">Tell me which group each stage belongs to. Saved in this browser only.</div><div class="rpscroll"><table class="rp sgt"><thead><tr><th class="l">Stage</th><th>Students</th><th class="l">Counts as</th></tr></thead><tbody>' +
    stNames.map((s, i) => '<tr><td class="l" title="' + esc(s) + '">' + esc(s) + '</td><td>' + (stCount[s] ? '<a class="dn" href="' + esc(lk([['STAGE_ID', s]])) + '">' + fmtN(stCount[s]) + '</a>' : '<span class="dash">·</span>') + '</td><td class="l"><select class="sgsel" data-i="' + i + '">' +
      GRP.map(g => '<option' + (g === stageGroup(s) ? ' selected' : '') + '>' + esc(g) + '</option>').join('') + '</select></td></tr>').join('') +
    '</tbody></table></div><button type="button" class="ghost" id="sgAuto">Reset to automatic guess</button>';
  h += '<div class="dgrid"><div class="chartcard"><h3>Re-registration % by semester</h3><div class="sub">Light bar = active students counted, dark green = done.</div>' + c1 + '</div>' +
    '<div class="chartcard"><h3>Where everyone stands</h3><div class="sub">Every student in view, by outcome.</div>' + c2 + '</div>' +
    '<div class="chartcard"><h3>Stage groups</h3>' + c3 + '</div></div>';
  // breakdown
  const g = {};
  list.forEach(r => {
    const v = fv(r, by.key), o = g[v] || (g[v] = {R: 0, O: 0, C: 0, N: 0});
    const s = rrStatus(r);
    o[s === RR.R ? 'R' : s === RR.O ? 'O' : s === RR.C ? 'C' : 'N']++;
  });
  const due = o => o.R + o.O;
  const names = Object.keys(g).sort((a, b) => due(g[b]) - due(g[a]) || a.localeCompare(b));
  const tot = {R: 0, O: 0, C: 0, N: 0}; names.forEach(v => Object.keys(tot).forEach(k => { tot[k] += g[v][k]; }));
  const cell = (num, extra) => num ? '<td><a class="dn" href="' + esc(lk(extra)) + '">' + fmtN(num) + '</a></td>' : '<td class="zero"><span>·</span></td>';
  const bar = (a, b) => '<td class="pcell"><div class="pb2 g"><i style="width:' + Math.min(100, pct(a, b)) + '%"></i><span>' + (b ? pct(a, b) + '%' : '–') + '</span></div></td>';
  const rowHtml = (label, o, pairs, cls) => { const p = pairs || []; return '<tr' + (cls ? ' class="' + cls + '"' : '') + '><td class="l" title="' + esc(label) + '">' + (pairs ? '<a class="dn" href="' + esc(lk(pairs)) + '">' + esc(label) + '</a>' : esc(label)) + '</td>' +
    cell(due(o), p.concat([[ST, '!' + RR.C]]).concat([[ST, '!' + RR.N]])) + cell(o.R, p.concat([[ST, RR.R]])) + cell(o.O, p.concat([[ST, RR.O]])) + bar(o.R, due(o)) + cell(o.C, p.concat([[ST, RR.C]])) + cell(o.N, p.concat([[ST, RR.N]])) + '</tr>'; };
  h += '<div class="chartcard wide"><h3>Re-registration by ' + esc(by.label.toLowerCase()) + '</h3><div class="sub">Sorted by most students due first. “Counted” = done + pending. Change “Break the table down by” above to see it by program, batch, stage and so on.</div><div class="rpscroll"><table class="rp"><thead><tr><th class="l">' + esc(by.label) + '</th><th>Counted</th><th>Done</th><th>Pending</th><th class="sub">Re-reg %</th><th>Completed</th><th>Not counted</th></tr></thead><tbody>' +
    names.map(v => rowHtml(v, g[v], [[by.key, v]], '')).join('') + rowHtml('Total', tot, null, 'gt') + '</tbody></table></div></div>';
  $('rpBody').innerHTML = h;
  $('rpBody').querySelectorAll('.sgsel').forEach(s => { s.onchange = () => { const o = sgLoad(); o[stNames[+s.dataset.i]] = s.value; sgSave(o); renderReport(); }; });
  const ar = $('sgAuto'); if (ar) ar.onclick = () => { sgSave({}); renderReport(); };
  const csv = [[by.label, 'Counted', 'Done', 'Pending', 'Re-reg %', 'Completed', 'Not counted'].map(csvCell).join(',')];
  names.concat(['Total']).forEach(v => { const o = v === 'Total' ? tot : g[v]; csv.push([v, due(o), o.R, o.O, pct(o.R, due(o)), o.C, o.N].map(csvCell).join(',')); });
  rpCache = {csv: csv, name: 're-reg-percentage-by-' + by.label.toLowerCase().replace(/[^a-z0-9]+/g, '-')};
}

/* ---- alumni report ---- */
function renderAlumni(def) {
  const rq = route.rq || {}, defs = fieldDefs();
  const by = defs.find(f => f.id === rq.by) || defs[0];
  const view = '';
  $('rpBuilder').hidden = false;
  $('rpBuilder').innerHTML = '<div class="rpt">View</div>' +
    selHtml('by', 'Break the table down by', defs.map(f => ({v: f.id, t: f.label})), rq.by && defs.some(f => f.id === rq.by) ? rq.by : '', by ? by.label : '—');
  $('rpFilters').innerHTML = '<div class="rpt">Filters</div>' + defs.map(f => {
    const vals = new Set(); rows.forEach(r => vals.add(fv(r, f.key)));
    return selHtml(f.id, f.label, orderVals(f.key, vals).map(v => ({v: v, t: v})), rq[f.id] || '', 'All', true);
  }).join('');
  enhanceSelects();
  const flt = repFilters();
  $('rpReset').disabled = !flt.length && !view;
  rpCache = null;
  const base = flt.map(f => [f.key, f.val]);
  const all = rows.filter(r => base.every(p => xMatch(r, p)));
  const isA = r => fv(r, '_ALUM') === 'Alumni';
  const alum = all.filter(isA);
  const rb = location.hash;
  const lk = extra => hashFor(null, {x: base.concat(extra), rb: rb}, true);
  const A = [['_ALUM', 'Alumni']], NA = [['_ALUM', 'Not alumni']];
  const pct = (a, b) => b ? Math.round(a / b * 1000) / 10 : 0;
  $('rpSummary').textContent = fmtN(alum.length) + ' alumni of ' + fmtN(all.length) + ' students · click any number to open those students';
  if (!all.length) { $('rpBody').innerHTML = '<div class="empty">No deals match these filters.</div>'; return; }
  const pk = progKey(), ak = colKey('OPPORTUNITY', /^amount$|^opportunity$/i);
  const uniN = new Set(alum.map(uniOf)).size, progN = pk ? new Set(alum.map(r => cellVal(r, pk))).size : 0;
  const sum = ak ? alum.reduce((a, r) => a + (parseFloat(r[ak]) || 0), 0) : 0;
  let h = '<div class="rpnote"><b>How this is worked out.</b> A student is an <b>alumnus</b> when their stage is grouped as <b>Completed</b>. That grouping is the same one used in the Re Reg Percentage report, which I guessed from the stage names (completed, graduated, won, alumni). <a class="dn" href="#/reports/rr">Check or change it there</a>.</div>';
  h += '<div class="hk">' +
    tile('Alumni', '🎓', 'var(--s3)', fmtN(alum.length), 'Completed their programme', lk(A)) +
    tile('Share of all students', '📊', 'var(--s1)', pct(alum.length, all.length) + '%', fmtN(alum.length) + ' of ' + fmtN(all.length) + ' students', lk(A)) +
    tile('Not alumni yet', '📚', 'var(--s4)', fmtN(all.length - alum.length), 'Still studying, dropped or refunded', lk(NA)) +
    tile('Universities', '🏛️', 'var(--s2)', fmtN(uniN), 'with at least one alumnus', lk(A)) +
    (pk ? tile('Programs', '🎯', 'var(--s5)', fmtN(progN), 'with at least one alumnus', lk(A)) : '') +
    '' + '</div>';
  if (!alum.length) h += '<div class="rpnote"><span class="od">No student is in a “Completed” stage right now.</span> If your alumni sit in a differently named stage, set it to “Completed” in the <a class="dn" href="#/reports/rr">Stage groups box</a>.</div>';
  // charts
  const hb = (title, sub, key, lab) => {
    const t = {}; alum.forEach(r => { const v = fv(r, key); t[v] = (t[v] || 0) + 1; });
    const ks = Object.keys(t).sort((a, b) => t[b] - t[a] || a.localeCompare(b)).slice(0, 10), mx = Math.max.apply(null, ks.map(k => t[k]).concat([1]));
    return '<div class="chartcard"><h3>' + esc(title) + '</h3><div class="sub">' + esc(sub) + '</div>' + (ks.length ? ks.map(k => '<a class="hbar" href="' + esc(lk(A.concat([[key, k]]))) + '"><span class="hl" title="' + esc(k) + '">' + esc(k) + '</span><span class="hb"><i style="width:' + Math.max(3, t[k] / mx * 100) + '%;background:var(--s3)"></i></span><span class="hn">' + fmtN(t[k]) + '</span></a>').join('') : '<div class="empty">No alumni</div>') + '</div>';
  };
  h += '<div class="dgrid">' + defs.slice(0, 4).map(f => hb('Alumni by ' + f.label.toLowerCase(), 'Top 10 · click a bar to open those alumni', f.key)).join('') + '</div>';
  // stages counted as alumni
  const st = {}; alum.forEach(r => { const v = r.STAGE_ID || '(no stage)'; st[v] = (st[v] || 0) + 1; });
  const sk = Object.keys(st).sort((a, b) => st[b] - st[a]);
  if (sk.length) h += '<div class="rpnote">Stages counted as alumni: ' + sk.map(k => '<a class="dn" href="' + esc(lk(A.concat([['STAGE_ID', k]]))) + '">' + esc(k) + ' (' + fmtN(st[k]) + ')</a>').join(' · ') + '</div>';
  // breakdown
  const g = {};
  all.forEach(r => { const v = fv(r, by.key), o = g[v] || (g[v] = {a: 0, n: 0}); if (isA(r)) o.a++; else o.n++; });
  const names = Object.keys(g).filter(v => g[v].a).sort((a, b) => (g[b].a - g[a].a) || a.localeCompare(b, undefined, {numeric: true}));
  const tot = {a: 0, n: 0}; names.forEach(v => { tot.a += g[v].a; tot.n += g[v].n; });
  const cell = (num, extra) => num ? '<td><a class="dn" href="' + esc(lk(extra)) + '">' + fmtN(num) + '</a></td>' : '<td class="zero"><span>·</span></td>';
  const bar = (a, b) => '<td class="pcell"><div class="pb2 g"><i style="width:' + Math.min(100, pct(a, b)) + '%"></i><span>' + (b ? pct(a, b) + '%' : '–') + '</span></div></td>';
  const row = (label, o, pairs, cls) => { const p = pairs || []; return '<tr' + (cls ? ' class="' + cls + '"' : '') + '><td class="l" title="' + esc(label) + '">' + (pairs ? '<a class="dn" href="' + esc(lk(pairs)) + '">' + esc(label) + '</a>' : esc(label)) + '</td>' +
    cell(o.a, p.concat(A)) + cell(o.n, p.concat(NA)) + cell(o.a + o.n, p) + bar(o.a, o.a + o.n) + '</tr>'; };
  h += '<div class="chartcard wide"><h3>Alumni by ' + esc(by.label.toLowerCase()) + '</h3><div class="sub">Sorted by most alumni first. Change “Break the table down by” above to see it by program, batch, stage and so on.</div><div class="rpscroll"><table class="rp"><thead><tr><th class="l">' + esc(by.label) + '</th><th>Alumni</th><th>Not alumni yet</th><th>All students</th><th class="sub">Alumni %</th></tr></thead><tbody>' +
    names.map(v => row(v, g[v], [[by.key, v]], '')).join('') + row('Total', tot, null, 'gt') + '</tbody></table></div></div>';
  $('rpBody').innerHTML = h;
  const csv = [[by.label, 'Alumni', 'Not alumni yet', 'All students', 'Alumni %'].map(csvCell).join(',')];
  names.concat(['Total']).forEach(v => { const o = v === 'Total' ? tot : g[v]; csv.push([v, o.a, o.n, o.a + o.n, pct(o.a, o.a + o.n)].map(csvCell).join(',')); });
  rpCache = {csv: csv, name: 'alumni-by-' + by.label.toLowerCase().replace(/[^a-z0-9]+/g, '-')};
}

/* ---- reference report ---- */
const REF_DEFS = [
  {id: 'has', label: 'Has reference', re: /^has\s*reference/i, alt: /^reference$/i},
  {id: 'adm', label: 'Reference admission', re: /reference\s*admission|admission.*reference/i},
  {id: 'lead', label: 'Reference lead', re: /reference\s*lead/i},
  {id: 'paid', label: 'Reference money paid', re: /reference\s*money|money.*reference/i}
];
let _rfCols = null, _rfVal = [];
function rfs() {
  if (_rfCols !== cols) {
    _rfCols = cols;
    _rfVal = REF_DEFS.map(d => { const c = cols.find(c => d.re.test(c.label)) || (d.alt ? cols.find(c => d.alt.test(c.label)) : null); return {id: d.id, label: d.label, key: c ? c.key : null, field: c ? c.label : null}; });
  }
  return _rfVal;
}
const rfKey = id => rfs().find(f => f.id === id).key;
function rv(r, id) { const k = rfKey(id); return k ? docVal(r, k) : 'Blank'; }
function refMoney(r) { const k = rfKey('paid'); if (!k) return 0; const m = /-?\d[\d,]*\.?\d*/.exec(String(r[k] || '').replace(/\s/g, '')); return m ? parseFloat(m[0].replace(/,/g, '')) || 0 : 0; }
const REF_S = ['Referred and admitted', 'Referred, not admitted', 'No reference', 'Check data'];
function refStatus(r) {
  const h = rv(r, 'has') === 'Yes', a = rv(r, 'adm') === 'Yes';
  return h && a ? REF_S[0] : h ? REF_S[1] : a ? REF_S[3] : REF_S[2];
}
function refFv(r, k) {
  if (k === '_REFS') return refStatus(r);
  if (k === '_REFHAS') return rv(r, 'has');
  if (k === '_REFADM') return rv(r, 'adm');
  if (k === '_REFPAID') return refMoney(r) > 0 ? 'Paid' : 'Not paid';
  if (k === '_MON') { const dk = createdKey(); return monthOf(r, dk) || 'Unknown'; }
  return null;
}
function renderRef(def) {
  const rq = route.rq || {}, defs = fieldDefs(), R = rfs();
  const hasK = rfKey('has'), admK = rfKey('adm'), paidK = rfKey('paid');
  const scope = rq.scope === 'active' ? 'active' : 'all';
  const VIEWS = [{v: 'has', t: 'Came through a reference'}, {v: 'adm', t: 'Reference admission = Yes'}, {v: 'pend', t: 'Referred, not admitted yet'}, {v: 'none', t: 'No reference'}, {v: 'chk', t: 'Data mismatch (admitted but no reference)'}];
  const view = VIEWS.some(x => x.v === rq.view) ? rq.view : '';
  const by = defs.find(f => f.id === rq.by) || defs[0];
  $('rpBuilder').hidden = false;
  $('rpBuilder').innerHTML = '<div class="rpt">View</div>' +
    selHtml('scope', 'Which deals', [{v: 'active', t: 'Active deals only'}], scope === 'active' ? 'active' : '', 'All deals') +
    selHtml('view', 'Quick view', VIEWS, view, 'Everyone') +
    selHtml('by', 'Break the table down by', defs.map(f => ({v: f.id, t: f.label})), rq.by && defs.some(f => f.id === rq.by) ? rq.by : '', by ? by.label : '—');
  $('rpFilters').innerHTML = '<div class="rpt">Filters</div>' + defs.map(f => {
    const vals = new Set(); rows.forEach(r => vals.add(fv(r, f.key)));
    return selHtml(f.id, f.label, orderVals(f.key, vals).map(v => ({v: v, t: v})), rq[f.id] || '', 'All', true);
  }).join('');
  enhanceSelects();
  const flt = repFilters();
  $('rpReset').disabled = !flt.length && !view && scope === 'all';
  rpCache = null;
  if (!hasK || !admK) {
    $('rpSummary').textContent = '';
    $('rpBody').innerHTML = '<div class="empty">I could not find ' + (!hasK ? '“Has reference”' : '') + (!hasK && !admK ? ' and ' : '') + (!admK ? '“Reference admission”' : '') + ' in your data. Press “Refresh data”, or send me the exact field names.</div>';
    return;
  }
  const base = flt.map(f => [f.key, f.val]);
  if (scope === 'active') base.push(['_ACT', 'Active']);
  const VP = {has: ['_REFHAS', 'Yes'], adm: ['_REFADM', 'Yes'], pend: ['_REFS', REF_S[1]], none: ['_REFS', REF_S[2]], chk: ['_REFS', REF_S[3]]};
  if (view) base.push(VP[view]);
  const list = rows.filter(r => base.every(p => xMatch(r, p)));
  const rb = location.hash;
  const lk = extra => hashFor(null, {x: base.concat(extra), rb: rb}, true);
  const n = list.length;
  $('rpSummary').textContent = fmtN(n) + ' students in view · ' + (scope === 'active' ? 'active deals only' : 'all deals') + ' · click any number to open those students';
  if (!n) { $('rpBody').innerHTML = '<div class="empty">No deals match these filters.</div>'; return; }
  const pct = (a, b) => b ? Math.round(a / b * 1000) / 10 : 0;
  const S = {}; REF_S.forEach(s => { S[s] = 0; });
  let money = 0, paidN = 0;
  list.forEach(r => { S[refStatus(r)]++; const m = refMoney(r); if (m > 0) { money += m; paidN++; } });
  const withRef = S[REF_S[0]] + S[REF_S[1]], admitted = S[REF_S[0]], notAdm = S[REF_S[1]], chk = S[REF_S[3]];
  let h = '<div class="rpnote">Fields used: ' + R.map(d => d.key ? '<b>' + esc(d.label) + '</b> → “' + esc(d.field) + '”' : '<b>' + esc(d.label) + '</b> → <span class="od">not found</span>').join(' · ') +
    '. <b>Has reference = Yes</b> means the student came through a reference. <b>Reference admission = Yes</b> means that reference joined. “Referred, not admitted” = has a reference but the admission is not Yes yet.';
  if (chk) h += ' <span class="od">' + fmtN(chk) + ' student' + (chk === 1 ? ' is' : 's are') + ' marked “Reference admission = Yes” without “Has reference = Yes”. Please check those.</span>';
  h += '</div>';
  h += '<div class="hk">' +
    tile('Students in view', '👥', 'var(--s1)', fmtN(n), scope === 'active' ? 'Active deals' : 'All deals', lk([])) +
    tile('Came through reference', '🤝', 'var(--s2)', fmtN(withRef), pct(withRef, n) + '% of students', lk([['_REFHAS', 'Yes']])) +
    tile('Admitted via reference', '🎓', 'var(--s3)', fmtN(admitted), pct(admitted, withRef) + '% of referred students joined', lk([['_REFS', REF_S[0]]])) +
    tile('Referred, not admitted yet', '⏳', '#e03131', fmtN(notAdm), 'Follow these up · click to open', lk([['_REFS', REF_S[1]]]), '', 'warn') +
    (paidK ? tile('Reference money paid', '💰', 'var(--s4)', money.toLocaleString('en-IN', {maximumFractionDigits: 0}), fmtN(paidN) + ' student' + (paidN === 1 ? '' : 's') + ' with an amount', lk([['_REFPAID', 'Paid']])) : '') + '</div>';
  // funnel
  const fr = (label, v, pairs, sub) => '<a class="hbar" href="' + esc(lk(pairs)) + '"><span class="hl">' + esc(label) + '</span><span class="hb"><i class="gi" style="width:' + Math.max(2, v / n * 100) + '%"></i></span><span class="hn">' + fmtN(v) + '</span></a><div class="hsub">' + esc(sub) + '</div>';
  const funnel = fr('All students', n, [], '100%') + fr('Came through reference', withRef, [['_REFHAS', 'Yes']], pct(withRef, n) + '% of all students') +
    fr('Admitted via reference', admitted, [['_REFS', REF_S[0]]], pct(admitted, withRef) + '% of referred students') + fr('Referred, not admitted yet', notAdm, [['_REFS', REF_S[1]]], pct(notAdm, withRef) + '% of referred students');
  // months
  const dk = createdKey();
  let monthsCard = '';
  if (dk) {
    const ms = lastMonths(12), mm = {};
    ms.forEach(k => { mm[k] = {n: 0, ref: 0, adm: 0}; });
    list.forEach(r => { const k = monthOf(r, dk); if (mm[k]) { mm[k].n++; const s = refStatus(r); if (s === REF_S[0] || s === REF_S[1]) mm[k].ref++; if (s === REF_S[0]) mm[k].adm++; } });
    const mx = Math.max.apply(null, ms.map(k => mm[k].ref).concat([1]));
    monthsCard = '<div class="chartcard"><h3>Reference students by month</h3><div class="sub">Grouped by the month the deal was created. Light bar = came through reference, dark green = admitted.</div>' +
      ms.map(k => '<a class="hbar hb2" href="' + esc(lk([['_MON', k], ['_REFHAS', 'Yes']])) + '"><span class="hl">' + MON[+k.slice(5) - 1] + " '" + k.slice(2, 4) + '</span><span class="hb"><i class="li2" style="width:' + (mm[k].ref / mx * 100) + '%"></i><i class="gi" style="width:' + (mm[k].adm / mx * 100) + '%"></i></span><span class="hn">' + fmtN(mm[k].adm) + ' / ' + fmtN(mm[k].ref) + '</span></a>').join('') + '</div>';
  }
  h += '<div class="dgrid"><div class="chartcard"><h3>Reference funnel</h3><div class="sub">From all students to those who joined through a reference.</div>' + funnel + '</div>' + monthsCard + '</div>';
  // breakdown
  const g = {};
  list.forEach(r => {
    const v = fv(r, by.key), o = g[v] || (g[v] = {n: 0, ref: 0, adm: 0, pend: 0, money: 0});
    o.n++; const s = refStatus(r);
    if (s === REF_S[0]) { o.ref++; o.adm++; } else if (s === REF_S[1]) { o.ref++; o.pend++; }
    o.money += refMoney(r);
  });
  const names = Object.keys(g).sort((a, b) => g[b].ref - g[a].ref || g[b].n - g[a].n || a.localeCompare(b));
  const cell = (num, extra) => num ? '<td><a class="dn" href="' + esc(lk(extra)) + '">' + fmtN(num) + '</a></td>' : '<td class="zero"><span>·</span></td>';
  const bar = (a, b) => '<td class="pcell"><div class="pb2 g"><i style="width:' + Math.min(100, pct(a, b)) + '%"></i><span>' + pct(a, b) + '%</span></div></td>';
  const tot = {n: n, ref: withRef, adm: admitted, pend: notAdm, money: money};
  const rowHtml = (label, o, pairs, cls) => { const p = pairs || []; return '<tr' + (cls ? ' class="' + cls + '"' : '') + '><td class="l" title="' + esc(label) + '">' + (pairs ? '<a class="dn" href="' + esc(lk(pairs)) + '">' + esc(label) + '</a>' : esc(label)) + '</td>' +
    cell(o.n, p) + cell(o.ref, p.concat([['_REFHAS', 'Yes']])) + bar(o.ref, o.n) + cell(o.adm, p.concat([['_REFS', REF_S[0]]])) + bar(o.adm, o.ref) + cell(o.pend, p.concat([['_REFS', REF_S[1]]])) +
    (paidK ? (o.money ? '<td><a class="dn" title="Open the students with an amount" href="' + esc(lk(p.concat([['_REFPAID', 'Paid']]))) + '">' + o.money.toLocaleString('en-IN', {maximumFractionDigits: 0}) + '</a></td>' : '<td class="zero"><span>·</span></td>') : '') + '</tr>'; };
  h += '<div class="chartcard wide"><h3>References by ' + esc(by.label.toLowerCase()) + '</h3><div class="sub">Sorted by most reference students first. Change “Break the table down by” above to see it by program, batch, stage and so on.</div><div class="rpscroll"><table class="rp"><thead><tr><th class="l">' + esc(by.label) + '</th><th>Students</th><th>With reference</th><th class="sub">Reference %</th><th>Admitted via reference</th><th class="sub">Conversion %</th><th>Not admitted yet</th>' + (paidK ? '<th>Money paid</th>' : '') + '</tr></thead><tbody>' +
    names.map(v => rowHtml(v, g[v], [[by.key, v]], '')).join('') + rowHtml('Total', tot, null, 'gt') + '</tbody></table></div></div>';
  $('rpBody').innerHTML = h;
  const csv = [[by.label, 'Students', 'With reference', 'Reference %', 'Admitted via reference', 'Conversion %', 'Not admitted yet'].concat(paidK ? ['Money paid'] : []).map(csvCell).join(',')];
  names.concat(['Total']).forEach(v => { const o = v === 'Total' ? tot : g[v]; csv.push([v, o.n, o.ref, pct(o.ref, o.n), o.adm, pct(o.adm, o.ref), o.pend].concat(paidK ? [o.money] : []).map(csvCell).join(',')); });
  rpCache = {csv: csv, name: 'references-by-' + by.label.toLowerCase().replace(/[^a-z0-9]+/g, '-')};
}


// The one field that drives the Documents pending report: "Documents Status" (UF_CRM_1790840655148).
const OLD_DOC_RES = [/(10\s*th|tenth).*certif|certif.*(10\s*th|tenth)/i, /(12\s*th|twelfth|diploma).*certif|certif.*(12\s*th|twelfth|diploma)/i, /degree.*certif|certif.*degree/i];
let _dfCols = null, _dfVal = [];
function dfs() {
  if (_dfCols !== cols) {
    _dfCols = cols;
    const c = cols.find(c => c.role === 'docstat') || cols.find(c => c.key === 'UF_CRM_1790840655148');
    _dfVal = [{id: 'ds', label: 'Documents Status', key: c ? c.key : null, field: c ? c.label : null}];
  }
  return _dfVal;
}
// Blank = not updated. Anything saying pending / incomplete / missing / not received / no = pending. Anything saying complete / received / submitted / verified / yes = complete.
// Any other wording is counted as pending, so nothing that is not clearly complete is hidden from the report.
function docClass(raw) {
  const v = String(raw || '').trim();
  if (v === '') return 'Not updated';
  if (/pend|incomplete|partial|missing|not\s*(yet\s*)?(receiv|submit|upload|complet|verif|provid|done)|await|waiting|yet to|short|deficien|^(no|n)$/i.test(v)) return 'Pending';
  if (/^(yes|y)$|complete|receiv|submit|verif|upload|approv|done|clear|^ok/i.test(v)) return 'Complete';
  return 'Pending';
}
function docVal(r, key) { const v = String(r[key] || '').trim(); return v === '' ? 'Blank' : v; }
function docStatus(r) {
  const d = dfs()[0];
  return d && d.key ? docClass(r[d.key]) : 'Not updated';
}
const AGE_B = ['0-30 days', '31-90 days', '91-180 days', '180+ days', 'Unknown'];
function ageBucket(r) {
  const dk = createdKey(), m = dk && /^(\d{4})-(\d{2})-(\d{2})/.exec(String(r[dk] || ''));
  if (!m) return 'Unknown';
  const days = (Date.now() - Date.UTC(+m[1], +m[2] - 1, +m[3])) / 864e5;
  return days <= 30 ? AGE_B[0] : days <= 90 ? AGE_B[1] : days <= 180 ? AGE_B[2] : AGE_B[3];
}
function fv(r, k) {
  if (k === '_DOCP') return docStatus(r);
  if (k === '_ACT') return isClosed(r) ? 'Closed' : 'Active';
  if (k === '_AGE') return ageBucket(r);
  if (k === '_LSG') return lsGroup(r);
  if (k.indexOf('_LS:') === 0) return lsVal(r, k.slice(4));
  if (k.indexOf('_DOC:') === 0) return docVal(r, k.slice(5));
  if (k.charAt(0) === '_') { let q = refFv(r, k); if (q !== null) return q; q = rrFv(r, k); if (q !== null) return q; }
  return k === uniKey() ? uniOf(r) : cellVal(r, k);
}
const MS = '\u001f';
function xMatch(r, p) {
  const neg = p[1].charAt(0) === '!', vs = (neg ? p[1].slice(1) : p[1]).split(MS);
  const inn = vs.indexOf(fv(r, p[0])) >= 0;
  return neg ? !inn : inn;
}
function xLabel(p) {
  const neg = p[1].charAt(0) === '!', v = (neg ? p[1].slice(1) : p[1]).split(MS).join(' or ');
  if (p[0] === '_DOCP') return 'Documents ' + v.toLowerCase();
  if (p[0] === '_ACT') return v + ' deals';
  if (p[0] === '_AGE') return 'Deal age ' + v;
  if (p[0] === '_REFS') return v;
  if (p[0] === '_REFHAS') return 'Has reference: ' + v;
  if (p[0] === '_REFADM') return 'Reference admission: ' + v;
  if (p[0] === '_REFPAID') return 'Reference money ' + v.toLowerCase();
  if (p[0] === '_MON') return 'Created ' + v;
  if (p[0] === '_RR' || p[0] === '_RRA') return (neg ? 'not ' : '') + v;
  if (p[0] === '_RRPAY') return 'Current semester fee: ' + v;
  if (p[0] === '_ALUM') return v;
  if (p[0] === '_LSG') return 'Lead status: ' + (neg ? 'not ' : '') + v;
  if (p[0].indexOf('_LS:') === 0) return 'Lead status: ' + (neg ? 'not ' : '') + v;
  if (p[0].indexOf('_DOC:') === 0) return 'Documents Status: ' + (neg ? 'not ' : '') + v;
  return (neg ? 'not ' : '') + v;
}
function shownCols() {
  const shown = cols.filter(c => visible.indexOf(c.key) >= 0);
  if (route.sheet && route.x.length) {
    const ps = route.x.map(p => p[0]);
    let want = [];
    if (ps.some(k => k.indexOf('_DOC') === 0)) want = want.concat(dfs().map(d => d.key));
    if (ps.some(k => k.indexOf('_LS') === 0)) { const lc = colByRole('lead'); if (lc) want.push(lc.key); }
    if (ps.some(k => k.indexOf('_REF') === 0)) want = want.concat(rfs().map(d => d.key));
    if (ps.some(k => k.indexOf('_RR') === 0)) want = want.concat([semKeyCur()]).concat(['lastpay', 'nextpay'].map(x => { const c = colByRole(x); return c && c.key; }));
    const add = want.filter(k => k && !shown.some(c => c.key === k)).map(k => cols.find(c => c.key === k));
    if (add.length) { const si = shown.findIndex(c => c.key === 'STAGE_ID'); shown.splice.apply(shown, [si >= 0 ? si + 1 : shown.length, 0].concat(add)); }
  }
  return shown;
}
/* ---------- LEAD STATUS (Connected / RNR) ---------- */
const LS_GROUPS = ['Connected', 'RNR', 'Other', 'Not updated'];
function lsKey() { const c = colByRole('lead'); return c ? c.key : null; }
function lsVal(r, k) { const v = String(r[k] || '').trim(); return v === '' ? 'Blank' : v; }
// RNR first (so "Not connected" / "Not picking" are RNR, never Connected). Anything unclear is Other, not guessed.
function lsClass(raw) {
  const v = String(raw || '').trim();
  if (v === '' || v === 'Blank') return 'Not updated';
  if (/\brnr\b|ring(ing)?\W+no\W+resp|no\W*(answer|response|reply)|not\W*(pick|answer|respond|reachab|connect|attend|lift|receiv)|un(answered|reachable|connected)|switch(ed)?\W*off|call\W*not\W*(pick|connect|answer)|busy|out\W*of\W*(reach|coverage|service)|not\W*available|voice\W*mail|invalid|wrong\W*number|disconnect/i.test(v)) return 'RNR';
  if (/connect|spoke|talk|contact|interest|hot|warm|cold|follow|call\W*back|callback|discuss|negotiat|convers|counsel|visit|enrol|admit|register|paid|document|think|later|busy\W*now/i.test(v)) return 'Connected';
  return 'Other';
}
function lsGroup(r) { const k = lsKey(); return k ? lsClass(r[k]) : 'Not updated'; }
function renderLead(def) {
  const rq = route.rq || {}, defs = fieldDefs(), lk0 = lsKey(), lc = colByRole('lead');
  const scope = rq.scope === 'all' ? 'all' : 'active';
  const by = defs.find(f => f.id === rq.by) || defs[0];
  const allVals = new Set(); if (lk0) rows.forEach(r => allVals.add(lsVal(r, lk0)));
  const valList = Array.from(allVals).sort((a, b) => (a === 'Blank') - (b === 'Blank') || a.localeCompare(b));
  const miss = valList.indexOf(rq.miss) >= 0 ? rq.miss : '';
  $('rpBuilder').hidden = false;
  $('rpBuilder').innerHTML = '<div class="rpt">View</div>' +
    selHtml('scope', 'Which deals', [{v: 'all', t: 'All deals (including completed / dropped)'}], scope === 'all' ? 'all' : '', 'Active deals only') +
    selHtml('miss', 'Lead Status is', valList.map(v => ({v: v, t: v})), miss, 'Any status') +
    selHtml('by', 'Break the table down by', defs.map(f => ({v: f.id, t: f.label})), rq.by && defs.some(f => f.id === rq.by) ? rq.by : '', by ? by.label : '—');
  $('rpFilters').innerHTML = '<div class="rpt">Filters</div>' + defs.map(f => {
    const vals = new Set(); rows.forEach(r => vals.add(fv(r, f.key)));
    return selHtml(f.id, f.label, orderVals(f.key, vals).map(v => ({v: v, t: v})), rq[f.id] || '', 'All', true);
  }).join('');
  enhanceSelects();
  const flt = repFilters();
  $('rpReset').disabled = !flt.length && !miss && scope === 'active';
  rpCache = null;
  if (!lk0) {
    $('rpSummary').textContent = '';
    $('rpBody').innerHTML = '<div class="empty">I could not find the “Lead Status” field in your data. Press “Refresh data” once, then open this report again.</div>';
    return;
  }
  const base = flt.map(f => [f.key, f.val]);
  if (scope === 'active') base.push(['_ACT', 'Active']);
  if (miss) base.push(['_LS:' + lk0, miss]);
  const list = rows.filter(r => base.every(p => xMatch(r, p)));
  const rb = location.hash;
  const lk = extra => hashFor(null, {x: base.concat(extra), rb: rb}, true);
  const n = list.length;
  $('rpSummary').textContent = fmtN(n) + ' students in view · ' + (scope === 'active' ? 'active deals only' : 'all deals') + ' · click any number to open those students';
  if (!n) { $('rpBody').innerHTML = '<div class="empty">No deals match these filters.</div>'; return; }
  const st = {Connected: 0, RNR: 0, Other: 0, 'Not updated': 0}, per = {};
  list.forEach(r => { const v = lsVal(r, lk0); per[v] = (per[v] || 0) + 1; st[lsClass(v)]++; });
  const pct = (a, b) => b ? Math.round(a / b * 1000) / 10 : 0;
  const vs = Object.keys(per).sort((a, b) => per[b] - per[a]);
  const byClass = c => vs.filter(v => lsClass(v) === c);
  let h = '<div class="rpnote">Field used: <b>Lead Status</b> → “' + esc(lc.label) + '”. ' +
    'Counted as <b>Connected</b>: ' + esc(byClass('Connected').join(', ') || '—') + '. Counted as <b>RNR</b>: ' + esc(byClass('RNR').join(', ') || '—') + '. <b>Other</b>: ' + esc(byClass('Other').join(', ') || '—') + '. <b>Not updated</b>: blank.' +
    ' If a value is in the wrong group, tell me which one.</div>';
  h += '<div class="hk">' +
    tile('Students in view', '👥', 'var(--s1)', fmtN(n), scope === 'active' ? 'Active deals' : 'All deals', lk([])) +
    tile('Connected', '✅', 'var(--s3)', fmtN(st.Connected), pct(st.Connected, n) + '% of students', lk([['_LSG', 'Connected']])) +
    tile('RNR', '📵', '#e03131', fmtN(st.RNR), pct(st.RNR, n) + '% · no response', lk([['_LSG', 'RNR']]), '', 'warn') +
    tile('Other', '🔹', 'var(--s4)', fmtN(st.Other), pct(st.Other, n) + '% of students', lk([['_LSG', 'Other']])) +
    tile('Not updated', '❔', 'var(--other)', fmtN(st['Not updated']), pct(st['Not updated'], n) + '% · status is blank', lk([['_LSG', 'Not updated']])) + '</div>';
  const clsColor = c => c === 'Connected' ? 'var(--s3)' : c === 'RNR' ? '#e03131' : c === 'Other' ? 'var(--s4)' : 'var(--other)';
  const vmax = Math.max.apply(null, vs.map(v => per[v]).concat([1]));
  const c1 = vs.map(v => '<a class="hbar" href="' + esc(lk([['_LS:' + lk0, v]])) + '"><span class="hl" title="' + esc(v) + '">' + esc(v) + '</span><span class="hb"><i style="width:' + Math.max(3, per[v] / vmax * 100) + '%;background:' + clsColor(lsClass(v)) + '"></i></span><span class="hn">' + fmtN(per[v]) + '</span></a>').join('');
  h += '<div class="dgrid"><div class="chartcard"><h3>By Lead Status</h3><div class="sub">Every value in the field and how many students have it. Click a bar to open them.</div>' + c1 + '</div></div>';
  const g = {};
  list.forEach(r => {
    const v = fv(r, by.key), o = g[v] || (g[v] = {n: 0, Connected: 0, RNR: 0, Other: 0, NU: 0});
    o.n++; const s = lsGroup(r);
    if (s === 'Connected') o.Connected++; else if (s === 'RNR') o.RNR++; else if (s === 'Other') o.Other++; else o.NU++;
  });
  const names = Object.keys(g).sort((a, b) => g[b].RNR - g[a].RNR || g[b].n - g[a].n || a.localeCompare(b));
  const cell = (num, extra) => num ? '<td><a class="dn" href="' + esc(lk(extra)) + '">' + fmtN(num) + '</a></td>' : '<td class="zero"><span>·</span></td>';
  const bar = (p, n2) => '<td class="pcell"><div class="pb2"><i style="width:' + Math.min(100, pct(p, n2)) + '%"></i><span>' + pct(p, n2) + '%</span></div></td>';
  const tot = {n: n, Connected: st.Connected, RNR: st.RNR, Other: st.Other, NU: st['Not updated']};
  const rowHtml = (label, o, pairs, cls) => '<tr' + (cls ? ' class="' + cls + '"' : '') + '><td class="l" title="' + esc(label) + '">' + (pairs ? '<a class="dn" href="' + esc(lk(pairs)) + '">' + esc(label) + '</a>' : esc(label)) + '</td>' +
    cell(o.n, pairs || []) + cell(o.Connected, (pairs || []).concat([['_LSG', 'Connected']])) + cell(o.RNR, (pairs || []).concat([['_LSG', 'RNR']])) + cell(o.Other, (pairs || []).concat([['_LSG', 'Other']])) + cell(o.NU, (pairs || []).concat([['_LSG', 'Not updated']])) + bar(o.RNR, o.n) + '</tr>';
  h += '<div class="chartcard wide"><h3>Connected vs RNR by ' + esc(by.label.toLowerCase()) + '</h3><div class="sub">Sorted by most RNR first. Change “Break the table down by” above to see it by program, batch, team and so on.</div><div class="rpscroll"><table class="rp"><thead><tr><th class="l">' + esc(by.label) + '</th><th>Students</th><th>Connected</th><th>RNR</th><th>Other</th><th>Not updated</th><th class="sub">RNR %</th></tr></thead><tbody>' +
    names.map(v => rowHtml(v, g[v], [[by.key, v]], '')).join('') + rowHtml('Total', tot, null, 'gt') + '</tbody></table></div></div>';
  $('rpBody').innerHTML = h;
  const csv = [[by.label, 'Students', 'Connected', 'RNR', 'Other', 'Not updated', 'RNR %'].map(csvCell).join(',')];
  names.concat(['Total']).forEach(v => { const o = v === 'Total' ? tot : g[v]; csv.push([v, o.n, o.Connected, o.RNR, o.Other, o.NU, pct(o.RNR, o.n)].map(csvCell).join(',')); });
  rpCache = {csv: csv, name: 'lead-status-by-' + by.label.toLowerCase().replace(/[^a-z0-9]+/g, '-')};
}
function renderDocs(def) {
  const rq = route.rq || {}, defs = fieldDefs(), d0 = dfs()[0], dk = d0 && d0.key;
  const scope = rq.scope === 'all' ? 'all' : 'active';
  const by = defs.find(f => f.id === rq.by) || defs[0];
  const allVals = new Set(); if (dk) rows.forEach(r => allVals.add(docVal(r, dk)));
  const valList = Array.from(allVals).sort((a, b) => (a === 'Blank') - (b === 'Blank') || a.localeCompare(b));
  const miss = valList.indexOf(rq.miss) >= 0 ? rq.miss : '';
  $('rpBuilder').hidden = false;
  $('rpBuilder').innerHTML = '<div class="rpt">View</div>' +
    selHtml('scope', 'Which deals', [{v: 'all', t: 'All deals (including completed / dropped)'}], scope === 'all' ? 'all' : '', 'Active deals only') +
    selHtml('miss', 'Documents Status is', valList.map(v => ({v: v, t: v})), miss, 'Any status') +
    selHtml('by', 'Break the table down by', defs.map(f => ({v: f.id, t: f.label})), rq.by && defs.some(f => f.id === rq.by) ? rq.by : '', by ? by.label : '—');
  $('rpFilters').innerHTML = '<div class="rpt">Filters</div>' + defs.map(f => {
    const vals = new Set(); rows.forEach(r => vals.add(fv(r, f.key)));
    return selHtml(f.id, f.label, orderVals(f.key, vals).map(v => ({v: v, t: v})), rq[f.id] || '', 'All', true);
  }).join('');
  enhanceSelects();
  const flt = repFilters();
  $('rpReset').disabled = !flt.length && !miss && scope === 'active';
  rpCache = null;
  if (!dk) {
    $('rpSummary').textContent = '';
    $('rpBody').innerHTML = '<div class="empty">I could not find the “Documents Status” field (UF_CRM_1790840655148) in your data. Press “Refresh data” once, then open this report again.</div>';
    return;
  }
  const base = flt.map(f => [f.key, f.val]);
  if (scope === 'active') base.push(['_ACT', 'Active']);
  if (miss) base.push(['_DOC:' + dk, miss]);
  const list = rows.filter(r => base.every(p => xMatch(r, p)));
  const rb = location.hash;
  const lk = extra => hashFor(null, {x: base.concat(extra), rb: rb}, true);
  const n = list.length;
  $('rpSummary').textContent = fmtN(n) + ' students in view · ' + (scope === 'active' ? 'active deals only' : 'all deals') + ' · click any number to open those students';
  if (!n) { $('rpBody').innerHTML = '<div class="empty">No deals match these filters.</div>'; return; }
  const st = {'Pending': 0, 'Complete': 0, 'Not updated': 0}, per = {}, age = {};
  list.forEach(r => {
    const s = docStatus(r); st[s]++;
    const v = docVal(r, dk); per[v] = (per[v] || 0) + 1;
    if (s === 'Pending') { const a = ageBucket(r); age[a] = (age[a] || 0) + 1; }
  });
  const pct = (a, b) => b ? Math.round(a / b * 1000) / 10 : 0;
  const vs = Object.keys(per).sort((a, b) => per[b] - per[a]);
  const byClass = c => vs.filter(v => docClass(v === 'Blank' ? '' : v) === c);
  let h = '<div class="rpnote">Field used: <b>Documents Status</b> → “' + esc(d0.field) + '”. ' +
    'Counted as <b>complete</b>: ' + esc(byClass('Complete').join(', ') || '—') + '. Counted as <b>pending</b>: ' + esc(byClass('Pending').join(', ') || '—') + '. <b>Not updated</b>: blank.' +
    ' If a value is in the wrong group, tell me which one.</div>';
  h += '<div class="hk">' +
    tile('Students in view', '👥', 'var(--s1)', fmtN(n), scope === 'active' ? 'Active deals' : 'All deals', lk([])) +
    tile('Documents pending', '📄', '#e03131', fmtN(st.Pending), pct(st.Pending, n) + '% of students', lk([['_DOCP', 'Pending']]), '', 'warn') +
    tile('Documents complete', '✅', 'var(--s3)', fmtN(st.Complete), pct(st.Complete, n) + '% of students', lk([['_DOCP', 'Complete']])) +
    tile('Not updated', '❔', 'var(--s4)', fmtN(st['Not updated']), pct(st['Not updated'], n) + '% · status is blank', lk([['_DOCP', 'Not updated']])) + '</div>';
  const clsColor = c => c === 'Complete' ? 'var(--s3)' : c === 'Pending' ? '#e03131' : 'var(--other)';
  const vmax = Math.max.apply(null, vs.map(v => per[v]).concat([1]));
  const c1 = vs.map(v => '<a class="hbar" href="' + esc(lk([['_DOC:' + dk, v]])) + '"><span class="hl" title="' + esc(v) + '">' + esc(v) + '</span><span class="hb"><i style="width:' + Math.max(3, per[v] / vmax * 100) + '%;background:' + clsColor(docClass(v === 'Blank' ? '' : v)) + '"></i></span><span class="hn">' + fmtN(per[v]) + '</span></a>').join('');
  const amax = Math.max.apply(null, AGE_B.map(b => age[b] || 0).concat([1]));
  const c3 = st.Pending ? AGE_B.filter(b => age[b]).map(b =>
    '<a class="hbar" href="' + esc(lk([['_DOCP', 'Pending'], ['_AGE', b]])) + '"><span class="hl">' + esc(b) + '</span><span class="hb"><i style="width:' + Math.max(3, age[b] / amax * 100) + '%"></i></span><span class="hn">' + fmtN(age[b]) + '</span></a>').join('') : '<div class="empty">Nobody is pending.</div>';
  h += '<div class="dgrid">' +
    '<div class="chartcard"><h3>By Documents Status</h3><div class="sub">Every value in the field and how many students have it. Click a bar to open them.</div>' + c1 + '</div>' +
    '<div class="chartcard"><h3>How long they have been pending</h3><div class="sub">Pending students by how old the deal is (days since created).</div>' + c3 + '</div></div>';
  const g = {};
  list.forEach(r => {
    const v = fv(r, by.key), o = g[v] || (g[v] = {n: 0, Pending: 0, Complete: 0, NU: 0});
    o.n++; const s = docStatus(r);
    if (s === 'Pending') o.Pending++; else if (s === 'Complete') o.Complete++; else o.NU++;
  });
  const names = Object.keys(g).sort((a, b) => g[b].Pending - g[a].Pending || g[b].n - g[a].n || a.localeCompare(b));
  const cell = (num, extra) => num ? '<td><a class="dn" href="' + esc(lk(extra)) + '">' + fmtN(num) + '</a></td>' : '<td class="zero"><span>·</span></td>';
  const bar = (p, n2) => '<td class="pcell"><div class="pb2"><i style="width:' + Math.min(100, pct(p, n2)) + '%"></i><span>' + pct(p, n2) + '%</span></div></td>';
  const tot = {n: n, Pending: st.Pending, Complete: st.Complete, NU: st['Not updated']};
  const rowHtml = (label, o, pairs, cls) => '<tr' + (cls ? ' class="' + cls + '"' : '') + '><td class="l" title="' + esc(label) + '">' + (pairs ? '<a class="dn" href="' + esc(lk(pairs)) + '">' + esc(label) + '</a>' : esc(label)) + '</td>' +
    cell(o.n, pairs || []) + cell(o.Complete, (pairs || []).concat([['_DOCP', 'Complete']])) + cell(o.Pending, (pairs || []).concat([['_DOCP', 'Pending']])) + cell(o.NU, (pairs || []).concat([['_DOCP', 'Not updated']])) + bar(o.Pending, o.n) + '</tr>';
  h += '<div class="chartcard wide"><h3>Documents pending by ' + esc(by.label.toLowerCase()) + '</h3><div class="sub">Sorted by most pending first. Change “Break the table down by” above to see it by program, batch, stage and so on.</div><div class="rpscroll"><table class="rp"><thead><tr><th class="l">' + esc(by.label) + '</th><th>Students</th><th>Complete</th><th>Pending</th><th>Not updated</th><th class="sub">Pending %</th></tr></thead><tbody>' +
    names.map(v => rowHtml(v, g[v], [[by.key, v]], '')).join('') + rowHtml('Total', tot, null, 'gt') + '</tbody></table></div></div>';
  $('rpBody').innerHTML = h;
  const csv = [[by.label, 'Students', 'Complete', 'Pending', 'Not updated', 'Pending %'].map(csvCell).join(',')];
  names.concat(['Total']).forEach(v => { const o = v === 'Total' ? tot : g[v]; csv.push([v, o.n, o.Complete, o.Pending, o.NU, pct(o.Pending, o.n)].map(csvCell).join(',')); });
  rpCache = {csv: csv, name: 'documents-pending-by-' + by.label.toLowerCase().replace(/[^a-z0-9]+/g, '-')};
}

const REP_KEY = 'pranav_reports_open';
const semKeyCur = () => colKey('UF_CRM_1784697183', /current\s*semester/i);
const batchKeyG = () => { const c = cols.find(c => /^batch\s*\(g\)/i.test(c.label)); return c ? c.key : colKey('UF_CRM_1784713245', /batch/i); };
function fieldDefs() {
  return [
    {id: 'uni', label: 'University', key: uniKey()},
    {id: 'prog', label: 'Program', key: progKey()},
    {id: 'batch', label: 'Batch', key: batchKeyG()},
    {id: 'mode', label: 'Mode of payment', key: modeKey()},
    {id: 'stage', label: 'Stage', key: 'STAGE_ID'},
    {id: 'sem', label: 'Current semester', key: semKeyCur()},
    // Admins only: a team login already sees just its own team.
    {id: 'team', label: 'Team Name', key: SCOPE ? null : ((colByRole('team') || {}).key || null)}
  ].filter(f => f.key && (f.key === 'STAGE_ID' || cols.some(c => c.key === f.key)));
}
function axisOptions() {
  const o = fieldDefs().map(f => ({key: f.key, label: f.label}));
  cols.filter(c => SEMRE.test(c.label)).sort((a, b) => semNum(a) - semNum(b)).forEach(c => o.push({key: c.key, label: c.label}));
  return o;
}
const axisLabel = k => { const o = axisOptions().find(x => x.key === k); return o ? o.label : colLabel(k); };
const REPORTS = [
  {id: 'reg', icon: '🔁', title: 'Re-registration tracker',
   desc: 'University and batch down the side. Stage across the top, with the current semester under each stage. Shows who has re-registered, who is pending payment, and which batches are stuck.',
   axes: () => ({rows: [uniKey(), batchKeyG()], cols: ['STAGE_ID', semKeyCur()]})},
  {id: 'docs', icon: '📄', title: 'Documents pending', kind: 'docs',
   desc: 'Students whose documents are still pending, based only on the “Documents Status” field. Includes what is missing, how long it has been pending, and a breakdown you can change.',
   axes: () => ({rows: [], cols: []})},
  {id: 'lead', icon: '📞', title: 'Lead status (Connected / RNR)', kind: 'lead',
   desc: 'Based only on the “Lead Status” field. Shows how many students are connected, how many are RNR (ringing, no response / not picking / switched off) and the rest, and every status value with its count. Click any number to open exactly those students. Break the table down by university, program, batch, team and more.',
   axes: () => ({rows: [], cols: []})},
  {id: 'ref', icon: '🤝', title: 'Reference report', kind: 'ref',
   desc: 'Students who came through a reference and how many of those references joined. Uses “Has reference” and “Reference admission”. Shows the funnel, references by month, who to follow up, and a breakdown you can change.',
   axes: () => ({rows: [], cols: []})},
  {id: 'rr', icon: '📈', title: 'Re Reg Percentage', kind: 'rr',
   desc: 'For active students only: Done when today falls between the Last Payment Date (P) and the Next Payment Date (P), Pending for everything else (no Next Payment Date, or past it). Students with no Last Payment Date (P) are not counted. Shows the percentage overall, by semester and by university, program or batch, and lets you decide which stage counts as Active.',
   axes: () => ({rows: [], cols: []})},
  {id: 'alumni', icon: '🎓', title: 'Alumni', kind: 'alumni',
   desc: 'Students who have completed their programme. Shows how many alumni you have, their share of all students, and where they come from (university, program, batch, mode of payment). A student counts as alumni when the stage is grouped as “Completed” in the Re Reg Percentage report.',
   axes: () => ({rows: [], cols: []})},
  {id: 'activity', icon: '🕵️', title: 'Activity log', kind: 'activity',
   desc: 'Who changed what, and when. Every save from this dashboard is recorded with the person who made it, the deal, each field that changed (old value → new value) and the comment they added. Sign-ins and sign-outs are listed too.',
   axes: () => ({rows: [], cols: []})},
  {id: 'custom', icon: '🧩', title: 'Custom report builder',
   desc: 'Build your own table. Pick what goes down the side and across the top from any field, then filter it by university, program, batch, mode of payment, stage or semester.',
   custom: true, axes: () => ({rows: [uniKey()], cols: ['STAGE_ID']})}
];

/* ---------- ACTIVITY LOG ---------- */
let actItems = [];
let actF = {user: '', type: '', from: '', to: '', q: ''};
function actRead() { actF = {user: $('actUser').value, type: $('actType').value, from: $('actFrom').value, to: $('actTo').value, q: $('actQ').value}; renderActivityRows(); }
function actClear() { actF = {user: '', type: '', from: '', to: '', q: ''}; ['actUser', 'actType', 'actFrom', 'actTo', 'actQ'].forEach(id => { $(id).value = ''; }); renderActivityRows(); }
function actWhen(iso) {
  const d = new Date(iso);
  if (isNaN(d)) return esc(iso || '');
  const p = n => String(n).padStart(2, '0');
  return p(d.getDate()) + '/' + p(d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + p(d.getHours()) + ':' + p(d.getMinutes());
}
function actColor(u) { let h = 0; for (let i = 0; i < u.length; i++) h = (h * 31 + u.charCodeAt(i)) >>> 0; return 'hsl(' + (h % 360) + ',55%,45%)'; }
function renderActivityRows() {
  const df = actF.from, dt = actF.to;
  const ymd = iso => { const d = new Date(iso); if (isNaN(d)) return ''; const p = n => String(n).padStart(2, '0'); return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate()); };
  const u = actF.user, t = actF.q.toLowerCase().trim(), ty = actF.type;
  const byId = {}; rows.forEach(r => { byId[r.ID] = r; });
  const items = actItems.filter(e => {
    if (u && e.user !== u) return false;
    if (df || dt) { const k = ymd(e.at); if (!k || (df && k < df) || (dt && k > dt)) return false; }
    if (ty === 'edit' && e.type) return false;
    if (ty === 'login' && !e.type) return false;
    if (t) {
      const r = byId[e.deal] || {};
      const hay = [e.user, e.deal, r._NAME || r.TITLE || '', e.comment || '', Object.values(e.changes || {}).map(c => c.field + ' ' + c.old + ' ' + c.new).join(' ')].join(' ').toLowerCase();
      if (hay.indexOf(t) < 0) return false;
    }
    return true;
  });
  $('rpSummary').textContent = fmtN(items.length) + ' of ' + fmtN(actItems.length) + ' entries';
  if (!items.length) { $('actTable').innerHTML = '<div class="empty">Nothing recorded yet. Saves made from this dashboard will appear here.</div>'; return; }
  $('actTable').innerHTML = '<table class="act"><thead><tr><th>When</th><th>Who</th><th>Deal</th><th>What happened</th></tr></thead><tbody>' + items.slice(0, 500).map(e => {
    const who = '<span class="who2" style="--c:' + actColor(e.user || '?') + '"><i>' + (avImg(e.user) || esc((e.user || '?').charAt(0).toUpperCase())) + '</i>' + esc(e.user || '') + '</span>';
    if (e.type) return '<tr><td class="w">' + actWhen(e.at) + '</td><td>' + who + '</td><td></td><td class="ev">' + (e.type === 'login' ? 'Signed in' : e.type === 'password' ? 'Changed password' : 'Signed out') + (e.ip ? ' · ' + esc(e.ip) : '') + '</td></tr>';
    const r = byId[e.deal];
    const deal = r ? '<button class="dl" data-id="' + esc(e.deal) + '">' + esc(r._NAME || r.TITLE || ('Deal ' + e.deal)) + '</button><br><small class="sub">#' + esc(e.deal) + '</small>' : '#' + esc(e.deal);
    const ch = Object.keys(e.changes || {}).filter(k => !(e.comment && /comment history/i.test(e.changes[k].field))).map(k => e.changes[k]).map(c => '<div><b>' + esc(c.field) + ':</b> ' + (c.old ? '<s>' + esc(c.old) + '</s> → ' : '') + '<b class="n">' + esc(c.new === '' ? '(empty)' : c.new) + '</b></div>').join('');
    const cm = e.comment ? '<div class="cmt">💬 ' + esc(e.comment) + (e.by && e.by !== e.user ? ' <small class="sub">— ' + esc(e.by) + '</small>' : '') + '</div>' : '';
    return '<tr><td class="w">' + actWhen(e.at) + '</td><td>' + who + '</td><td>' + deal + '</td><td><div class="chgs">' + ch + '</div>' + cm + '</td></tr>';
  }).join('') + '</tbody></table>' + (items.length > 500 ? '<div class="sub" style="margin-top:8px">Showing the latest 500. Use the filters to narrow down.</div>' : '');
  $('actTable').querySelectorAll('button.dl').forEach(b => { b.onclick = () => { const r = rows.find(x => x.ID === b.dataset.id); if (r) openEdit(r); }; });
}
async function renderActivity(def) {
  $('rpBuilder').hidden = true; $('rpBuilder').innerHTML = '';
  $('rpFilters').innerHTML = '';
  $('rpReset').disabled = true;
  rpCache = null;
  $('rpSummary').textContent = 'Loading…';
  $('rpBody').innerHTML = '<div class="actbar"><select id="actUser"><option value="">Everyone</option></select><select id="actType"><option value="">All activity</option><option value="edit">Changes only</option><option value="login">Sign-ins only</option></select><label class="actd">From <input id="actFrom" type="date"></label><label class="actd">To <input id="actTo" type="date"></label><span class="actq"><button type="button" class="ghost" data-r="0">Today</button><button type="button" class="ghost" data-r="1">Yesterday</button><button type="button" class="ghost" data-r="7">Last 7 days</button><button type="button" class="ghost" data-r="x">Any date</button></span><input id="actQ" type="search" placeholder="Search deal, student, field or comment…" style="min-width:240px"><button type="button" id="actApply" class="actap">Apply</button><button type="button" id="actClear" class="ghost">Clear</button></div><div id="actTable"><div class="empty">Loading…</div></div>';
  let j = null;
  try {
    const r = await fetch('api.php?action=activity', {credentials: 'same-origin'});
    if (r.status === 401) { location.reload(); return; }
    j = await r.json();
  } catch (e) {}
  if (route.id !== 'activity' || !$('actTable')) return;
  if (!j || !j.ok) { $('rpSummary').textContent = ''; $('actTable').innerHTML = '<div class="empty">Could not load the activity log.</div>'; return; }
  mergeAV(j); actItems = j.items || [];
  const users = Array.from(new Set(actItems.map(e => e.user).filter(Boolean))).sort();
  $('actUser').innerHTML = '<option value="">Everyone</option>' + users.map(u => '<option>' + esc(u) + '</option>').join('');
  $('actApply').onclick = actRead; $('actClear').onclick = actClear;
  const ds = n => { const d = new Date(); d.setDate(d.getDate() - n); const p = x => String(x).padStart(2, '0'); return d.getFullYear() + '-' + p(d.getMonth() + 1) + '-' + p(d.getDate()); };
  document.querySelectorAll('.actq button').forEach(b => { b.onclick = () => {
    const r = b.dataset.r;
    $('actFrom').value = r === 'x' ? '' : ds(r === '7' ? 6 : +r);
    $('actTo').value = r === 'x' ? '' : ds(r === '1' ? 1 : 0);
    actRead();
  }; });
  $('actQ').onkeydown = e => { if (e.key === 'Enter') actRead(); };
  actClear();
}
const isLast = v => v === NONE || v === UNASSIGNED;
function orderVals(key, vals) {
  const arr = Array.from(vals);
  const ix = s => { const i = stagesList.indexOf(s); return i < 0 ? 999 : i; };
  return arr.sort((a, b) => (isLast(a) - isLast(b)) || (key === 'STAGE_ID' ? ix(a) - ix(b) : 0) || a.localeCompare(b, undefined, {numeric: true, sensitivity: 'base'}));
}
function pivot(list, rk, ck) {
  const M = new Map(), S = {r1: new Set(), r2: {}, c1: new Set(), c2: {}};
  list.forEach(r => {
    const r1 = fv(r, rk[0]), r2 = rk[1] ? fv(r, rk[1]) : '*', c1 = fv(r, ck[0]), c2 = ck[1] ? fv(r, ck[1]) : '*';
    S.r1.add(r1); (S.r2[r1] = S.r2[r1] || new Set()).add(r2);
    S.c1.add(c1); (S.c2[c1] = S.c2[c1] || new Set()).add(c2);
    const seen = new Set();
    [r1, '*'].forEach(a => [r2, '*'].forEach(b => [c1, '*'].forEach(c => [c2, '*'].forEach(d => { seen.add(a + '\u0001' + b + '\u0001' + c + '\u0001' + d); }))));
    seen.forEach(k => M.set(k, (M.get(k) || 0) + 1));
  });
  return {count: (a, b, c, d) => M.get(a + '\u0001' + b + '\u0001' + c + '\u0001' + d) || 0,
    r1: orderVals(rk[0], S.r1), r2: v => rk[1] ? orderVals(rk[1], S.r2[v] || []) : ['*'],
    c1: orderVals(ck[0], S.c1), c2: v => ck[1] ? orderVals(ck[1], S.c2[v] || []) : ['*']};
}
function repAxes(def) {
  const rq = route.rq || {}, d = def.axes();
  if (!def.custom) return d;
  const ok = k => k && axisOptions().some(o => o.key === k);
  const rows = [ok(rq.r1) ? rq.r1 : d.rows[0], ok(rq.r2) ? rq.r2 : null].filter(Boolean);
  const cs = [ok(rq.c1) ? rq.c1 : d.cols[0], ok(rq.c2) ? rq.c2 : null].filter(Boolean);
  return {rows: rows, cols: cs};
}
function repFilters() {
  const rq = route.rq || {}, out = [];
  fieldDefs().forEach(f => { if (rq[f.id]) out.push({id: f.id, label: f.label, key: f.key, val: rq[f.id]}); });
  return out;
}
let rpCache = null;
function setRepOpen(open) { $('rnav').hidden = !open; $('navR').setAttribute('aria-expanded', open ? 'true' : 'false'); lss(REP_KEY, open); }
$('navR').onclick = () => { if (inRail()) { expandRail(); setRepOpen(true); return; } setRepOpen($('rnav').hidden); };
setRepOpen(lsg(REP_KEY));
function renderRepNav() {
  const cur = route.type === 'report' ? route.id : '';
  $('rnav').innerHTML = '<a href="#/reports" class="' + (route.type === 'reports' ? 'on' : '') + '"><b>All reports</b></a>' +
    REPORTS.map(r => '<a href="#/reports/' + r.id + '" class="' + (cur === r.id ? 'on' : '') + '"><b title="' + esc(r.title) + '">' + r.icon + ' ' + esc(r.title) + '</b></a>').join('');
  if ((route.type === 'report' || route.type === 'reports') && $('rnav').hidden) setRepOpen(true);
}
function showReportsView(reset) {
  ['home', 'overview', 'dash'].forEach(id => { $(id).hidden = true; });
  $('reports').hidden = route.type !== 'reports';
  $('report').hidden = route.type !== 'report';
  document.querySelector('.wrap').classList.remove('full');
  $('shell').classList.remove('nosb');
  if (reset) window.scrollTo(0, 0);
  renderSide();
  if (route.type === 'reports') {
    document.title = 'Reports · Pranav Pipeline';
    $('rpList').innerHTML = REPORTS.map(r => '<a class="rcard" href="#/reports/' + r.id + '"><span class="ri">' + r.icon + '</span><div><b>' + esc(r.title) + '</b><p>' + esc(r.desc) + '</p><span class="go">Open report →</span></div></a>').join('');
  } else {
    renderReport();
  }
}
function selHtml(param, label, opts, cur, blank, multi) {
  if (multi) {
    const cs = cur ? String(cur).split(MS) : [];
    return '<label>' + esc(label) + '<select data-p="' + esc(param) + '" data-m="1" data-v="' + esc(cs.join(MS)) + '" multiple' + (cs.length ? ' class="on"' : '') + '>' +
      opts.map(o => '<option value="' + esc(o.v) + '">' + esc(o.t) + '</option>').join('') + '</select></label>';
  }
  return '<label>' + esc(label) + '<select data-p="' + esc(param) + '"' + (cur ? ' class="on"' : '') + '><option value="">' + esc(blank) + '</option>' +
    opts.map(o => '<option value="' + esc(o.v) + '"' + (o.v === cur ? ' selected' : '') + '>' + esc(o.t) + '</option>').join('') + '</select></label>';
}
function enhanceMulti(sel) {
  sel.style.display = 'none';
  const wrap = document.createElement('div'); wrap.className = 'cb';
  const all = Array.from(sel.options).map(o => ({v: o.value, t: o.text}));
  let chosen = new Set(sel.dataset.v ? sel.dataset.v.split(MS) : []);
  const label = set => set.size === 0 ? 'All' : set.size === 1 ? all.find(o => set.has(o.v)).t : set.size + ' selected';
  const btn = document.createElement('button'); btn.type = 'button'; btn.className = 'cbb' + (chosen.size ? ' on' : '');
  btn.innerHTML = '<span class="cbt"></span><span class="cbc">▾</span>';
  btn.querySelector('.cbt').textContent = label(chosen);
  const panel = document.createElement('div'); panel.className = 'cbp'; panel.hidden = true;
  panel.innerHTML = '<input type="search" placeholder="Type to search…" autocomplete="off"><div class="cbl"></div><div class="cbf"><button type="button" class="ghost cbsh">Select all shown</button><button type="button" class="ghost cbcl">Clear</button><button type="button" class="cbap">Apply</button></div>';
  wrap.appendChild(btn); wrap.appendChild(panel); sel.parentNode.insertBefore(wrap, sel.nextSibling);
  const input = panel.querySelector('input'), list = panel.querySelector('.cbl');
  let act = 0, items = [];
  const draw = (kb) => {
    const st = list.scrollTop;
    const q = input.value.toLowerCase().trim();
    items = all.filter(o => !q || o.t.toLowerCase().indexOf(q) >= 0);
    if (act >= items.length) act = Math.max(0, items.length - 1);
    list.innerHTML = items.length ? items.map((o, i) => '<div class="cbo chk' + (chosen.has(o.v) ? ' sel on' : '') + (i === act ? ' act' : '') + '" data-v="' + esc(o.v) + '">' + esc(o.t) + '</div>').join('') : '<div class="cbn">No match</div>';
    panel.querySelector('.cbap').textContent = 'Apply' + (chosen.size ? ' (' + chosen.size + ')' : '');
    list.scrollTop = st;
    if (kb === true) { const a = list.querySelector('.act'); if (a && a.scrollIntoView) a.scrollIntoView({block: 'nearest'}); }
  };
  const orig = Array.from(chosen).sort().join(MS);
  const apply = () => {
    panel.hidden = true;
    const now = Array.from(chosen).sort().join(MS);
    if (now === orig) return;
    sel.dataset.v = Array.from(chosen).join(MS);
    sel.dispatchEvent(new Event('change', {bubbles: true}));
  };
  panel._apply = apply;
  panel.addEventListener('click', e => e.stopPropagation());
  const toggle = v => { if (chosen.has(v)) chosen.delete(v); else chosen.add(v); draw(); };
  btn.onclick = e => {
    e.preventDefault();
    const open = panel.hidden;
    document.querySelectorAll('.cbp').forEach(p => { p.hidden = true; });
    if (open) { panel.hidden = false; input.value = ''; act = 0; draw(); input.focus(); }
  };
  input.oninput = () => { act = 0; draw(); };
  input.onkeydown = e => {
    if (e.key === 'ArrowDown') { e.preventDefault(); act = Math.min(items.length - 1, act + 1); draw(true); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); act = Math.max(0, act - 1); draw(true); }
    else if (e.key === 'Enter') { e.preventDefault(); if (e.shiftKey || !items[act]) apply(); else toggle(items[act].v); }
    else if (e.key === 'Escape') { panel.hidden = true; btn.focus(); }
  };
  list.onclick = e => { const o = e.target.closest('.cbo'); if (o) { act = Array.prototype.indexOf.call(list.children, o); toggle(o.dataset.v); } };
  panel.querySelector('.cbsh').onclick = () => { items.forEach(o => chosen.add(o.v)); draw(); };
  panel.querySelector('.cbcl').onclick = () => { chosen.clear(); draw(); };
  panel.querySelector('.cbap').onclick = apply;
}
function enhanceSelects() {
  document.querySelectorAll('#report select[data-p]').forEach(sel => {
    sel.style.display = 'none';
    if (sel.multiple) { enhanceMulti(sel); return; }
    const wrap = document.createElement('div'); wrap.className = 'cb';
    const btn = document.createElement('button'); btn.type = 'button'; btn.className = 'cbb' + (sel.value ? ' on' : '');
    btn.innerHTML = '<span class="cbt"></span><span class="cbc">▾</span>';
    btn.querySelector('.cbt').textContent = sel.options[sel.selectedIndex].text;
    const panel = document.createElement('div'); panel.className = 'cbp'; panel.hidden = true;
    panel.innerHTML = '<input type="search" placeholder="Type to search…" autocomplete="off"><div class="cbl"></div>';
    wrap.appendChild(btn); wrap.appendChild(panel); sel.parentNode.insertBefore(wrap, sel.nextSibling);
    const input = panel.querySelector('input'), list = panel.querySelector('.cbl');
    let act = 0, items = [];
    const draw = () => {
      const q = input.value.toLowerCase().trim();
      items = Array.from(sel.options).filter(o => !q || o.text.toLowerCase().indexOf(q) >= 0);
      if (act >= items.length) act = Math.max(0, items.length - 1);
      list.innerHTML = items.length ? items.map((o, i) => '<div class="cbo' + (o.value === sel.value ? ' sel' : '') + (i === act ? ' act' : '') + '" data-v="' + esc(o.value) + '">' + esc(o.text) + '</div>').join('') : '<div class="cbn">No match</div>';
      const a = list.querySelector('.act'); if (a && a.scrollIntoView) a.scrollIntoView({block: 'nearest'});
    };
    const pick = v => { panel.hidden = true; sel.value = v; sel.dispatchEvent(new Event('change', {bubbles: true})); };
    btn.onclick = e => {
      e.preventDefault();
      const open = panel.hidden;
      document.querySelectorAll('.cbp').forEach(p => { p.hidden = true; });
      if (open) { panel.hidden = false; input.value = ''; act = Math.max(0, Array.from(sel.options).findIndex(o => o.value === sel.value)); draw(); input.focus(); }
    };
    input.oninput = () => { act = 0; draw(); };
    input.onkeydown = e => {
      if (e.key === 'ArrowDown') { e.preventDefault(); act = Math.min(items.length - 1, act + 1); draw(); }
      else if (e.key === 'ArrowUp') { e.preventDefault(); act = Math.max(0, act - 1); draw(); }
      else if (e.key === 'Enter') { e.preventDefault(); if (items[act]) pick(items[act].value); }
      else if (e.key === 'Escape') { panel.hidden = true; btn.focus(); }
    };
    list.onclick = e => { const o = e.target.closest('.cbo'); if (o) pick(o.dataset.v); };
  });
}
document.addEventListener('click', e => { if (e.target.matches && e.target.matches('select[data-p],select[data-g]')) return; if (!e.composedPath().some(n => n.classList && n.classList.contains('cb'))) document.querySelectorAll('.cbp').forEach(p => { if (!p.hidden && p._apply) p._apply(); else p.hidden = true; }); });
function renderReport() {
  const def = REPORTS.find(r => r.id === route.id);
  if (!def) { location.hash = '#/reports'; return; }
  $('rpTitle').textContent = def.icon + ' ' + def.title;
  $('rpDesc').textContent = def.desc;
  document.title = def.title + ' · Pranav Pipeline';
  $('rpFilters').hidden = def.kind === 'activity'; $('rpXlsx').hidden = def.kind === 'activity';
  if (def.kind === 'docs') { renderDocs(def); return; }
  if (def.kind === 'lead') { renderLead(def); return; }
  if (def.kind === 'ref') { renderRef(def); return; }
  if (def.kind === 'rr') { renderRR(def); return; }
  if (def.kind === 'alumni') { renderAlumni(def); return; }
  if (def.kind === 'activity') { renderActivity(def); return; }
  const rq = route.rq || {}, defs = fieldDefs(), ax = repAxes(def);
  const missing = ax.rows.concat(ax.cols).some(k => !k || (k !== 'STAGE_ID' && !cols.some(c => c.key === k)));
  // builder
  const bd = $('rpBuilder');
  bd.hidden = !def.custom;
  if (def.custom) {
    const ao = axisOptions().map(o => ({v: o.key, t: o.label}));
    bd.innerHTML = '<div class="rpt">Build your table</div>' +
      selHtml('r1', 'Down the side (1st)', ao, ax.rows[0], '— choose —') + selHtml('r2', 'Down the side (2nd, optional)', ao, ax.rows[1] || '', 'None') +
      selHtml('c1', 'Across the top (1st)', ao, ax.cols[0], '— choose —') + selHtml('c2', 'Across the top (2nd, optional)', ao, ax.cols[1] || '', 'None');
  }
  // filters
  $('rpFilters').innerHTML = '<div class="rpt">Filters</div>' + defs.map(f => {
    const vals = new Set(); rows.forEach(r => vals.add(fv(r, f.key)));
    return selHtml(f.id, f.label, orderVals(f.key, vals).map(v => ({v: v, t: v})), rq[f.id] || '', 'All', true);
  }).join('');
  enhanceSelects();
  const flt = repFilters();
  const list = rows.filter(r => flt.every(f => xMatch(r, [f.key, f.val])));
  $('rpReset').disabled = !flt.length;
  if (missing) { $('rpBody').innerHTML = '<div class="empty">This report needs a field that is not in your Bitrix24 data yet (' + esc(ax.rows.concat(ax.cols).filter(k => !k).length ? 'Batch, University or Current semester' : 'a chosen field') + '). Press "Refresh data", or pick other fields.</div>'; $('rpSummary').textContent = ''; rpCache = null; return; }
  if (!list.length) { $('rpBody').innerHTML = '<div class="empty">No deals match these filters.</div>'; $('rpSummary').textContent = '0 deals'; rpCache = null; return; }
  const P = pivot(list, ax.rows, ax.cols);
  rpCache = {P: P, ax: ax, def: def, flt: flt, n: list.length};
  const two = ax.cols.length === 2, tworow = ax.rows.length === 2;
  let max = 1;
  P.r1.forEach(a => P.r2(a).forEach(b => P.c1.forEach(c => P.c2(c).forEach(d => { max = Math.max(max, P.count(a, b, c, d)); }))));
  const btn = (a, b, c, d, n, heat, lbl) => n || lbl ?
    '<button class="mxb' + (heat ? ' heat' + (Math.sqrt(n / max) > 0.6 ? ' dk' : '') : ' h') + '"' + (heat ? ' style="--i:' + (0.14 + 0.76 * Math.sqrt(n / max)).toFixed(2) + '"' : '') +
    ' data-a="' + esc(a) + '" data-b="' + esc(b) + '" data-c="' + esc(c) + '" data-d="' + esc(d) + '">' + (lbl ? esc(lbl) : fmtN(n)) + '</button>' : '';
  const cell = (a, b, c, d, heat, cls) => { const n = P.count(a, b, c, d); return n ? '<td' + (cls ? ' class="' + cls + '"' : '') + '>' + btn(a, b, c, d, n, heat) + '</td>' : '<td class="zero' + (cls ? ' ' + cls : '') + '"><span>·</span></td>'; };
  const cells = (a, b) => {
    let h = '';
    P.c1.forEach(c => {
      if (two) { P.c2(c).forEach(d => { h += cell(a, b, c, d, b !== '*' || !tworow, ''); }); h += cell(a, b, c, '*', false, 'st'); }
      else h += cell(a, b, c, '*', b !== '*' || !tworow, '');
    });
    return h + cell(a, b, '*', '*', false, 'st');
  };
  let h = '<div class="rpscroll"><table class="rp"><thead><tr><th class="l"' + (two ? ' rowspan="2"' : '') + '>' + esc(ax.rows.map(axisLabel).join(' → ')) + '</th>';
  P.c1.forEach(c => { h += two ? '<th class="cg" colspan="' + (P.c2(c).length + 1) + '">' + esc(c) + '</th>' : '<th>' + esc(c) + '</th>'; });
  h += '<th class="sub"' + (two ? ' rowspan="2"' : '') + '>Total</th></tr>';
  if (two) { h += '<tr>'; P.c1.forEach(c => { P.c2(c).forEach(d => { h += '<th>' + esc(d) + '</th>'; }); h += '<th class="sub">Subtotal</th>'; }); h += '</tr>'; }
  h += '</thead><tbody>';
  P.r1.forEach(a => {
    if (tworow) {
      h += '<tr class="grp"><td class="l">' + btn(a, '*', '*', '*', 1, false, a) + '</td>' + cells(a, '*') + '</tr>';
      P.r2(a).forEach(b => { h += '<tr><td class="l ind" title="' + esc(b) + '">' + btn(a, b, '*', '*', 1, false, b) + '</td>' + cells(a, b) + '</tr>'; });
    } else {
      h += '<tr><td class="l" title="' + esc(a) + '">' + btn(a, '*', '*', '*', 1, false, a) + '</td>' + cells(a, '*') + '</tr>';
    }
  });
  h += '<tr class="gt"><td class="l">Total</td>' + cells('*', '*') + '</tr></tbody></table></div>';
  $('rpBody').innerHTML = h;
  $('rpSummary').textContent = fmtN(list.length) + ' deals · ' + fmtN(P.r1.length) + ' row group' + (P.r1.length === 1 ? '' : 's') + ' · click any number to open those students';
}
function rpQuery() {
  const p = new URLSearchParams();
  document.querySelectorAll('#report select[data-p]').forEach(s => { const v = s.multiple ? (s.dataset.v || '') : s.value; if (v) p.set(s.dataset.p, v); });
  return p.toString();
}
function rpNavigate(qs) { history.replaceState(null, '', '#/reports/' + route.id + (qs ? '?' + qs : '')); route = parseRoute(); renderReport(); }
$('report').addEventListener('change', e => { if (e.target.matches('select[data-p]')) rpNavigate(rpQuery()); });
$('rpReset').onclick = () => { const def = REPORTS.find(r => r.id === route.id); const p = new URLSearchParams(); if (def && (def.custom || def.kind)) ['r1', 'r2', 'c1', 'c2', 'by'].forEach(k => { const s = document.querySelector('#rpBuilder select[data-p="' + k + '"]'); if (s && s.value) p.set(k, s.value); }); rpNavigate(p.toString()); };
$('rpBody').addEventListener('click', e => {
  const b = e.target.closest('button.mxb');
  if (!b || !rpCache) return;
  const {ax, flt} = rpCache, m = new Map();
  flt.forEach(f => m.set(f.key, f.val));
  [['a', ax.rows[0]], ['b', ax.rows[1]], ['c', ax.cols[0]], ['d', ax.cols[1]]].forEach(p => { const v = b.dataset[p[0]]; if (p[1] && v !== '*') m.set(p[1], v); });
  location.hash = hashFor(null, {x: Array.from(m.entries()), rb: location.hash}, true);
});
/* ---------- Excel export: one coloured table sheet + one "Deals" sheet that every number links into ---------- */
const XL = (function () {
  const enc = new TextEncoder();
  const XH = '<' + '?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
  const crcT = (() => { const t = []; for (let n = 0; n < 256; n++) { let c = n; for (let k = 0; k < 8; k++) c = c & 1 ? 0xEDB88320 ^ (c >>> 1) : c >>> 1; t[n] = c >>> 0; } return t; })();
  const crc = b => { let c = 0xFFFFFFFF; for (let i = 0; i < b.length; i++) c = crcT[(c ^ b[i]) & 255] ^ (c >>> 8); return (c ^ 0xFFFFFFFF) >>> 0; };
  const u16 = v => [v & 255, (v >> 8) & 255], u32 = v => [v & 255, (v >> 8) & 255, (v >> 16) & 255, (v >>> 24) & 255];
  async function deflate(data) {
    if (typeof CompressionStream === 'undefined') return null;
    try { return new Uint8Array(await new Response(new Blob([data]).stream().pipeThrough(new CompressionStream('deflate-raw'))).arrayBuffer()); } catch (e) { return null; }
  }
  async function zip(files) {
    const parts = [], cd = []; let off = 0;
    for (const f of files) {
      const nm = enc.encode(f.name), raw = enc.encode(f.text), c = crc(raw), usz = raw.length;
      const z = await deflate(raw), data = z && z.length < usz ? z : raw, meth = data === raw ? 0 : 8, sz = data.length;
      const lh = new Uint8Array([0x50, 0x4b, 3, 4, ...u16(20), ...u16(0x0800), ...u16(meth), ...u16(0), ...u16(0x21), ...u32(c), ...u32(sz), ...u32(usz), ...u16(nm.length), ...u16(0)]);
      parts.push(lh, nm, data);
      cd.push(new Uint8Array([0x50, 0x4b, 1, 2, ...u16(20), ...u16(20), ...u16(0x0800), ...u16(meth), ...u16(0), ...u16(0x21), ...u32(c), ...u32(sz), ...u32(usz), ...u16(nm.length), ...u16(0), ...u16(0), ...u16(0), ...u16(0), ...u32(0), ...u32(off)]), nm);
      off += lh.length + nm.length + sz;
    }
    let cs = 0; cd.forEach(x => { cs += x.length; });
    const end = new Uint8Array([0x50, 0x4b, 5, 6, 0, 0, 0, 0, ...u16(files.length), ...u16(files.length), ...u32(cs), ...u32(off), 0, 0]);
    return new Blob(parts.concat(cd, [end]), {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
  }
  const X = s => String(s == null ? '' : s).replace(/[\u0000-\u0008\u000B\u000C\u000E-\u001F]/g, '').replace(/[<>&"]/g, c => ({'<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;'}[c]));
  const colName = i => { let s = ''; i++; while (i > 0) { const m = (i - 1) % 26; s = String.fromCharCode(65 + m) + s; i = Math.floor((i - 1) / 26); } return s; };
  // style registry: o = {b, u, sz, c (font colour), f (fill), bd (border), h (align), w (wrap), pct}
  function Styles() {
    const fonts = ['<font><sz val="11"/><color rgb="FF000000"/><name val="Calibri"/></font>'];
    const fills = ['<fill><patternFill patternType="none"/></fill>', '<fill><patternFill patternType="gray125"/></fill>'];
    const borders = ['<border><left/><right/><top/><bottom/><diagonal/></border>', '<border><left style="thin"><color rgb="FF9FB3BC"/></left><right style="thin"><color rgb="FF9FB3BC"/></right><top style="thin"><color rgb="FF9FB3BC"/></top><bottom style="thin"><color rgb="FF9FB3BC"/></bottom><diagonal/></border>'];
    const xfs = ['<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'], map = new Map();
    const idx = (arr, x) => { let i = arr.indexOf(x); if (i < 0) { arr.push(x); i = arr.length - 1; } return i; };
    this.id = o => {
      if (!o) return 0;
      const key = JSON.stringify(o); if (map.has(key)) return map.get(key);
      const f = idx(fonts, '<font>' + (o.b ? '<b/>' : '') + (o.u ? '<u/>' : '') + '<sz val="' + (o.sz || 11) + '"/><color rgb="FF' + (o.c || '000000') + '"/><name val="Calibri"/></font>');
      const fl = o.f ? idx(fills, '<fill><patternFill patternType="solid"><fgColor rgb="FF' + o.f + '"/><bgColor indexed="64"/></patternFill></fill>') : 0;
      xfs.push('<xf numFmtId="' + (o.pct ? 164 : 0) + '" fontId="' + f + '" fillId="' + fl + '" borderId="' + (o.bd ? 1 : 0) + '" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment' + (o.h ? ' horizontal="' + o.h + '"' : '') + ' vertical="center"' + (o.w ? ' wrapText="1"' : '') + '/></xf>');
      map.set(key, xfs.length - 1); return xfs.length - 1;
    };
    this.xml = () => XH + '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><numFmts count="1"><numFmt numFmtId="164" formatCode="0.0%"/></numFmts><fonts count="' + fonts.length + '">' + fonts.join('') + '</fonts><fills count="' + fills.length + '">' + fills.join('') + '</fills><borders count="' + borders.length + '">' + borders.join('') + '</borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="' + xfs.length + '">' + xfs.join('') + '</cellXfs><cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles></styleSheet>';
  }
  // sheet = {name, rows: [[cell|null]], widths, merges, first, heights}; cell = {v, o, link}
  function sheetXml(sh, S) {
    let x = XH + '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' + (sh.tab ? '<sheetPr><tabColor rgb="FF' + sh.tab + '"/></sheetPr>' : '') + '<sheetViews><sheetView workbookViewId="0"' + (sh.grid ? '' : ' showGridLines="0"') + (sh.first ? ' tabSelected="1"' : '') + (sh.freeze ? '><pane ySplit="' + sh.freeze + '" topLeftCell="A' + (sh.freeze + 1) + '" activePane="bottomLeft" state="frozen"/></sheetView>' : '/>') + '</sheetViews><sheetFormatPr defaultRowHeight="16"/>';
    x += '<cols>' + sh.widths.map((w, i) => '<col min="' + (i + 1) + '" max="' + (i + 1) + '" width="' + w + '" customWidth="1"/>').join('') + '</cols><sheetData>';
    const links = [];
    sh.rows.forEach((row, ri) => {
      const ht = sh.heights && sh.heights[ri];
      x += '<row r="' + (ri + 1) + '"' + (ht ? ' ht="' + ht + '" customHeight="1"' : '') + '>';
      row.forEach((c, ci) => {
        if (!c) return;
        const ref = colName(ci) + (ri + 1), s = S.id(c.o), st = s ? ' s="' + s + '"' : '';
        if (c.v === '' || c.v == null) { if (s) x += '<c r="' + ref + '"' + st + '/>'; return; }
        if (typeof c.v === 'number') x += '<c r="' + ref + '"' + st + '><v>' + c.v + '</v></c>';
        else x += '<c r="' + ref + '"' + st + ' t="inlineStr"><is><t xml:space="preserve">' + X(c.v) + '</t></is></c>';
        if (c.link) links.push('<hyperlink ref="' + ref + '" location="' + X(c.link) + '" display="' + X(typeof c.v === 'number' ? c.v : c.v) + '"/>');
      });
      x += '</row>';
    });
    x += '</sheetData>' + (sh.merges && sh.merges.length ? '<mergeCells count="' + sh.merges.length + '">' + sh.merges.map(m => '<mergeCell ref="' + m + '"/>').join('') + '</mergeCells>' : '') + (links.length ? '<hyperlinks>' + links.join('') + '</hyperlinks>' : '') + '</worksheet>';
    return x;
  }
  async function build(sheets) {
    const S = new Styles(), files = [];
    const sx = sheets.map(s => sheetXml(s, S));
    files.push({name: '[Content_Types].xml', text: XH + '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>' + sheets.map((s, i) => '<Override PartName="/xl/worksheets/sheet' + (i + 1) + '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>').join('') + '</Types>'});
    files.push({name: '_rels/.rels', text: XH + '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>'});
    files.push({name: 'xl/workbook.xml', text: XH + '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><bookViews><workbookView activeTab="0"/></bookViews><sheets>' + sheets.map((s, i) => '<sheet name="' + X(s.name) + '" sheetId="' + (i + 1) + '" r:id="rId' + (i + 1) + '"/>').join('') + '</sheets></workbook>'});
    files.push({name: 'xl/_rels/workbook.xml.rels', text: XH + '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' + sheets.map((s, i) => '<Relationship Id="rId' + (i + 1) + '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' + (i + 1) + '.xml"/>').join('') + '<Relationship Id="rId' + (sheets.length + 1) + '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>'});
    files.push({name: 'xl/styles.xml', text: S.xml()});
    sx.forEach((t, i) => files.push({name: 'xl/worksheets/sheet' + (i + 1) + '.xml', text: t}));
    return await zip(files);
  }
  return {build: build};
})();

function xlRowsForLink(el) {
  // returns {key, rows} for an <a class="dn" href> or a matrix button, or null
  if (el.matches('button.mxb') && rpCache && rpCache.ax) {
    const m = new Map(); rpCache.flt.forEach(f => m.set(f.key, f.val));
    [['a', rpCache.ax.rows[0]], ['b', rpCache.ax.rows[1]], ['c', rpCache.ax.cols[0]], ['d', rpCache.ax.cols[1]]].forEach(p => { const v = el.dataset[p[0]]; if (p[1] && v !== '*') m.set(p[1], v); });
    const pairs = Array.from(m.entries());
    return {key: JSON.stringify(pairs), rows: allRows.filter(r => pairs.every(p => xMatch(r, p)))};
  }
  const href = el.getAttribute('href') || ''; if (href.charAt(0) !== '#') return null;
  const qi = href.indexOf('?'), path = qi >= 0 ? href.slice(1, qi) : href.slice(1), sp = new URLSearchParams(qi >= 0 ? href.slice(qi + 1) : '');
  let base = allRows, mt;
  if ((mt = /^\/u\/([^/]*)\/sheet$/.exec(path))) { let nm = ''; try { nm = decodeURIComponent(mt[1]); } catch (e) { return null; } base = allRows.filter(r => nm.split(MS).indexOf(uniOf(r)) >= 0); }
  else if (!/^\/master\/sheet$/.test(path)) return null;
  const pairs = sp.getAll('x').map(t => { const i = t.indexOf('='); return [t.slice(0, i), t.slice(i + 1)]; }).filter(p => p[0]);
  let list = base.filter(r => pairs.every(p => xMatch(r, p)));
  if (sp.get('f')) { try { list = list.filter(r => passF(r, sp.get('f'))); } catch (e) {} }
  if (sp.get('bot')) { const bs = botSel(sp.get('bot')); if (bs) list = allRows.filter(r => bs.ids.has(String(r.ID))); }
  return {key: href.replace(/&rb=[^&]*/, ''), rows: list};
}
// light colours that carry a meaning: green = good/done, orange = waiting/problem, grey = left out, teal scale = plain counts
function xlMeaning(label) {
  const l = String(label || '').toLowerCase();
  if (/not counted|left out/.test(l)) return 'ECEFF1';
  if (/pending|rnr|overdue|missing|unpaid|refund|dropped|not alumni|not admitted|not updated|no reference|without/.test(l)) return 'FFE3C7';
  if (/done|connected|complete|alumni|admitted|joined|paid|with reference/.test(l)) return 'D5F2E0';
  return null;
}
async function downloadExcel() {
  const tables = Array.from(document.querySelectorAll('#rpBody table.rp:not(.sgt)'));
  if (!tables.length) return false;
  const detailCols = shownCols(), cap = 1500, TB = 'Table', used = new Set(['table']);
  const blocks = new Map();   // key -> {rows, title, origin, start}
  const out = [], merges = [], heights = {}, widths = [32];
  const hint = {v: 'Click any underlined number: the deals behind it are on their own sheet (tab at the bottom). Each of those sheets has a “← Back to table” link.', o: {c: '4B5E66', sz: 10}};
  out.push([{v: 'Pranav Pipeline report', o: {b: 1, sz: 16, c: '0F5E59'}}]); heights[0] = 26;
  out.push([hint]); out.push([]);
  const blockList = [];
  tables.forEach((t, ti) => {
    const g = [], trs = Array.from(t.rows);
    trs.forEach((tr, ri) => { g[ri] = g[ri] || []; let ci = 0; Array.from(tr.cells).forEach(td => { while (g[ri][ci]) ci++; const rs = td.rowSpan || 1, cs = td.colSpan || 1, o = {el: td, ri: ri, ci: ci, rs: rs, cs: cs}; for (let a = 0; a < rs; a++) for (let b = 0; b < cs; b++) { g[ri + a] = g[ri + a] || []; g[ri + a][ci + b] = (a || b) ? {dup: true, of: o} : o; } ci += cs; }); });
    const hr = t.tHead ? t.tHead.rows.length : 1, txt = el => el.textContent.replace(/\s+/g, ' ').trim();
    const width = Math.max.apply(null, g.map(r => r.length));
    for (let i = 0; i < width; i++) if (widths[i] == null) widths[i] = 14;
    const colLabel = ci => { const a = []; for (let h = 0; h < hr; h++) { let c = g[h] && g[h][ci]; if (c && c.dup) c = c.of; if (c) { const s = txt(c.el); if (s && a.indexOf(s) < 0) a.push(s); } } return a.join(' › '); };
    const card = t.closest('.chartcard'), h3 = card && card.querySelector('h3');
    if (tables.length > 1 || h3) { if (h3) { out.push([{v: txt(h3), o: {b: 1, sz: 13, c: '0F5E59'}}]); heights[out.length - 1] = 22; } }
    // heat scale for plain count cells
    let max = 1;
    g.forEach((row, ri) => { if (ri < hr) return; row.forEach(c => { if (c && !c.dup && !c.el.classList.contains('st') && !c.el.classList.contains('l') && !c.el.classList.contains('pcell') && !c.el.parentNode.classList.contains('gt')) { const n = Number(txt(c.el).replace(/,/g, '')); if (isFinite(n)) max = Math.max(max, n); } }); });
    const heat = n => { const k = Math.sqrt(Math.max(0, Math.min(1, n / max))), lo = [0xF3, 0xFB, 0xFA], hi = [0xA9, 0xE0, 0xD8]; return lo.map((v, i) => Math.round(v + (hi[i] - v) * k).toString(16).padStart(2, '0')).join('').toUpperCase(); };
    g.forEach((row, ri) => {
      const line = [], tr = trs[ri], isTot = tr.classList.contains('gt'), isGrp = tr.classList.contains('grp'), rowLab = row[0] && !row[0].dup ? txt(row[0].el) : '';
      const outRow = out.length;
      for (let ci = 0; ci < width; ci++) {
        const c = row[ci];
        if (!c) { line.push(null); continue; }
        if (c.dup) { const o = c.of, src = o.el, isHead = o.ri < hr; line.push({v: '', o: isHead ? {f: src.classList.contains('cg') ? 'CFE3F8' : src.classList.contains('sub') ? 'FFEFBE' : 'D4EEEA', bd: 1} : {f: 'F1FAF9', bd: 1}}); continue; }
        const el = c.el; let v = txt(el); if (v === '·' || v === '–') v = '';
        const cell = {v: v, o: {bd: 1, h: el.classList.contains('l') ? 'left' : 'center'}};
        if (ri < hr) {
          cell.o = {bd: 1, b: 1, w: 1, h: 'center', c: '123F3B', f: el.classList.contains('cg') ? 'CFE3F8' : el.classList.contains('sub') ? 'FFEFBE' : 'D4EEEA'};
          if (ci === 0) cell.o.h = 'left';
          if (c.cs > 1 || c.rs > 1) merges.push(colName0(ci) + (outRow + 1) + ':' + colName0(ci + c.cs - 1) + (outRow + c.rs));
        } else {
          const pm = /^(-?\d+(?:\.\d+)?)%$/.exec(v);
          if (pm) { cell.v = Number(pm[1]) / 100; cell.o.pct = 1; cell.o.f = 'FFF6CF'; }
          else if (/^-?[\d,]+(\.\d+)?$/.test(v) && !el.classList.contains('l')) cell.v = Number(v.replace(/,/g, ''));
          if (el.classList.contains('l')) { cell.o.f = isTot ? 'FFEFBE' : isGrp ? 'D4EEEA' : 'F1FAF9'; if (isTot || isGrp) cell.o.b = 1; if (el.classList.contains('ind')) cell.o.h = 'left'; }
          else if (isTot) { cell.o.f = 'FFEFBE'; cell.o.b = 1; }
          else if (el.classList.contains('st')) { cell.o.f = 'FFF6DA'; cell.o.b = 1; }
          else if (!pm) { const mean = xlMeaning(colLabel(ci)); cell.o.f = mean || (typeof cell.v === 'number' ? heat(cell.v) : 'FFFFFF'); }
          const lk = el.querySelector('a.dn[href], button.mxb');
          if (lk && v !== '') {
            const res = xlRowsForLink(lk);
            if (res && res.rows.length) {
              let b = blocks.get(res.key);
              if (!b && blocks.size < cap) {
                const lab = (ci === 0 ? rowLab : (rowLab + ' › ' + colLabel(ci))).replace(/\s*›\s*$/, '');
                b = {rows: res.rows, name: xlSheetName(((lab || 'Deals').slice(0, 31 - (' (' + res.rows.length + ')').length).trim()) + ' (' + res.rows.length + ')', used), origin: colName0(ci) + (outRow + 1)}; blocks.set(res.key, b); blockList.push(b);
              }
              if (b) { cell.blk = b; cell.o.u = 1; cell.o.c = '0B5CAD'; }
            }
          }
          if (ci === 0) widths[0] = Math.min(60, Math.max(widths[0], String(v).length + 3));
        }
        line.push(cell);
      }
      out.push(line);
    });
    out.push([]);
  });
  // one sheet per number: only that number's deals (header row + deals); a small back link sits to the right
  out.forEach(row => row.forEach(c => { if (c && c.blk) { c.link = "'" + c.blk.name.replace(/'/g, "''") + "'!A1"; delete c.blk; } }));
  const sheets = [{name: TB, rows: out, widths: widths, merges: merges, heights: heights, first: true, tab: 'F2C14E'}];
  const nc = detailCols.length;
  blockList.forEach(b => {
    const rws = [detailCols.map(c => ({v: c.label, o: {b: 1, c: '123F3B', f: 'DCEBFA', bd: 1, h: 'left', w: 1}}))];
    rws[0][nc + 1] = {v: '← Back to table', o: {u: 1, c: '0B5CAD'}, link: "'" + TB + "'!" + b.origin};
    b.rows.forEach((r, k) => rws.push(detailCols.map(c => ({v: String(r[c.key] == null ? '' : r[c.key]), o: {bd: 1, f: k % 2 ? 'F6FBFA' : 'FFFFFF'}}))));
    const w = detailCols.map(c => { let m = String(c.label).length; for (let k = 0; k < Math.min(b.rows.length, 120); k++) m = Math.max(m, String(r0(b.rows[k], c)).length); return Math.min(38, Math.max(11, m + 3)); });
    w.push(3, 18);
    sheets.push({name: b.name, rows: rws, widths: w, freeze: 1, grid: 1, tab: '8FD6CC'});
  });
  const blob = await XL.build(sheets), a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = 'pranav-' + ((rpCache && rpCache.name) || route.id || 'report') + '-' + new Date().toISOString().slice(0, 10) + '.xlsx';
  document.body.appendChild(a); a.click(); a.remove();
  setTimeout(() => URL.revokeObjectURL(a.href), 4000);
  window.__xlLast = {sheets: sheets.length, details: blockList.length};
  return true;
}
const r0 = (r, c) => r[c.key] == null ? '' : r[c.key];
function xlSheetName(want, used) {
  let n = String(want || 'Sheet').replace(/[\\\/?*\[\]:]/g, '-').replace(/^'+|'+$/g, '').trim().slice(0, 31) || 'Sheet';
  let k = 1, t = n;
  while (used.has(t.toLowerCase())) { k++; const sfx = ' (' + k + ')'; t = n.slice(0, 31 - sfx.length) + sfx; }
  used.add(t.toLowerCase()); return t;
}
function colName0(i) { let s = ''; i++; while (i > 0) { const m = (i - 1) % 26; s = String.fromCharCode(65 + m) + s; i = Math.floor((i - 1) / 26); } return s; }
$('rpXlsx').onclick = async () => { const b = $('rpXlsx'), t = b.textContent; b.disabled = true; b.textContent = 'Preparing…'; try { await downloadExcel(); } catch (e) { toast('Could not build the Excel file: ' + (e && e.message || e)); } b.disabled = false; b.textContent = t; };

function renderAll() {
  renderColsList();
  renderRoute(false);
}

function passNoStage(r, terms) {
  const pk = progKey(), mk = modeKey();
  if (route.p !== null && pk && cellVal(r, pk) !== route.p) return false;
  if (route.m !== null && mk && cellVal(r, mk) !== route.m) return false;
  for (const t of terms) if (r._s.indexOf(t) < 0) return false;
  return true;
}
function renderStages() {
  const counts = {}, all = {};
  const terms = query.toLowerCase().split(/\s+/).filter(Boolean);
  const base = baseRows();
  const rows = base.filter(r => passNoStage(r, terms));
  base.forEach(r => { all[r.STAGE_ID || '(no stage)'] = 1; });
  rows.forEach(r => { const s = r.STAGE_ID || '(no stage)'; counts[s] = (counts[s] || 0) + 1; });
  const sel0 = stage === '' ? [] : stage.split(MS);
  const names = stagesList.filter(s => all[s]).concat(Object.keys(all).filter(s => stagesList.indexOf(s) < 0)).filter(s => counts[s] || sel0.indexOf(s) >= 0);
  counts.__z = 0;
  const sel = sel0;
  let html = '<button class="chip' + (stage === '' ? ' on' : '') + '" data-s="">All (' + rows.length + ')</button>';
  names.forEach(n => {
    html += '<button class="chip' + (sel.indexOf(n) >= 0 ? ' on' : '') + '" data-s="' + esc(n) + '">' + esc(n) + ' (' + (counts[n] || 0) + ')</button>';
  });
  if (sel.length > 1) html += '<span class="sub">' + sel.length + ' stages selected</span>';
  $('stages').innerHTML = base.length ? html : '';
  $('stages').querySelectorAll('.chip').forEach(b => b.onclick = () => { const v = b.dataset.s; if (v === '') stage = ''; else { const c = stage === '' ? [] : stage.split(MS), i = c.indexOf(v); if (i >= 0) c.splice(i, 1); else c.push(v); stage = c.join(MS); } page = 0; renderStages(); apply(); });
}

function renderColsList() {
  $('colsList').innerHTML = cols.map(c =>
    '<label><input type="checkbox" data-k="' + esc(c.key) + '"' + (visible.indexOf(c.key) >= 0 ? ' checked' : '') + '> <span>' + esc(c.label) + '</span></label>'
  ).join('');
  $('colsList').querySelectorAll('input').forEach(i => i.onchange = () => {
    visible = cols.map(c => c.key).filter(k => $('colsList').querySelector('input[data-k="' + k + '"]').checked);
    lsSet(visible);
    page = 0;
    apply();
  });
}

const isNum = v => /^-?\d+(\.\d+)?$/.test(v);

function apply() {
  const terms = query.toLowerCase().split(/\s+/).filter(Boolean);
  const pk = progKey(), mk = modeKey();
  view = baseRows().filter(r => {
    if (!passNoStage(r, terms)) return false;
    if (stage !== '' && stage.split(MS).indexOf(r.STAGE_ID || '(no stage)') < 0) return false;
    return true;
  });
  renderStages();
  if (!sortKey) {
    // Default order: deals nobody has updated come first, the most recently updated go to the very end.
    view.sort((a, b) => actOf(a) - actOf(b));
  } else {
    view.sort((a, b) => {
      const va = (sortKey === '_COMMENT_AT' ? a._ct : a[sortKey]) || '', vb = (sortKey === '_COMMENT_AT' ? b._ct : b[sortKey]) || '';
      if (va === '' && vb === '') return 0;
      if (va === '') return 1;
      if (vb === '') return -1;
      if (isNum(va) && isNum(vb)) return (parseFloat(va) - parseFloat(vb)) * sortDir;
      return va.localeCompare(vb, undefined, {numeric: true, sensitivity: 'base'}) * sortDir;
    });
  }
  renderTable();
}

function pill(text, color) {
  return '<span class="pill" style="--c:' + color + '"><i></i>' + esc(text) + '</span>';
}
function stageColor(n) {
  const order = stagesList.concat([]);
  const i = order.indexOf(n);
  return i >= 0 && i < 7 ? 'var(--s' + (i + 1) + ')' : 'var(--other)';
}
function hashColor(v) {
  let h = 0;
  for (let i = 0; i < v.length; i++) h = (h * 31 + v.charCodeAt(i)) >>> 0;
  return 'var(--s' + (h % 7 + 1) + ')';
}
function cellHtml(c, v, row) {
  if (v === '') return '<span class="dash">–</span>';
  if (c.key === 'STAGE_ID') return pill(v, stageColor(v));
  if (SEMRE.test(c.label)) return pill(v, /^paid$/i.test(v) ? 'var(--s3)' : /not/i.test(v) ? 'var(--s2)' : 'var(--other)');
  if (rfs().some(d => (d.id === 'has' || d.id === 'adm') && d.key === c.key)) return pill(v, /^(yes|y)$/i.test(v) ? 'var(--s3)' : 'var(--other)');
  if (dfs().some(d => d.key === c.key)) return pill(v, docClass(v) === 'Complete' ? 'var(--s3)' : docClass(v) === 'Pending' ? '#e03131' : 'var(--other)');
  if (c.type === 'boolean') return pill(v, v === 'Yes' ? 'var(--s3)' : 'var(--other)');
  if (c.key === progKey() || c.key === modeKey() || c.key === batchKey()) return pill(v, hashColor(v));
  if (c.key === '_COMMENT') return '<span class="cm">' + esc(v) + '</span>';
  if (c.key === 'ID') return '<span class="idc">' + esc(v) + '</span>';
  if (c.key === '_NAME') return '<b class="nm">' + esc(v) + '</b>';
  if (c.key === '_PHONE') { const d = String(v).split(/[,;]/)[0].replace(/\D/g, ''); if (d.length >= 10) { const ph = d.length === 10 ? '91' + d : d; return '<span style="display:inline-flex;align-items:center;gap:10px;white-space:nowrap">' + esc(v) + '<a class="wapp" href="https://wa.me/' + esc(ph) + '" data-ph="' + esc(ph) + '" data-id="' + esc(row ? row.ID : '') + '" title="Open WhatsApp chat" aria-label="Open WhatsApp chat"><svg viewBox="0 0 24 24"><path class="b" d="M12 2.5a9.5 9.5 0 0 0-8.1 14.4L2.7 21.5l4.7-1.2A9.5 9.5 0 1 0 12 2.5z"/><path class="h" d="M16.7 14.5c-.2-.1-1.4-.7-1.6-.8-.2-.1-.4-.1-.5.1l-.8 1c-.1.2-.3.2-.5.1-1.3-.6-2.3-1.4-3.1-2.8-.1-.2 0-.3.1-.4l.6-.7c.1-.1.1-.3 0-.4l-.7-1.7c-.2-.4-.3-.4-.5-.4h-.4c-.2 0-.4.1-.6.3-.6.6-.9 1.3-.8 2.2.1.9.5 1.8 1.2 2.7 1.2 1.7 2.7 2.7 4.5 3.2.8.2 1.4.2 2-.2.5-.3.8-.8.9-1.3 0-.2 0-.4-.2-.5z"/></svg></a></span>'; } }
  return esc(v);
}

function renderTable() {
  const shown = shownCols();
  const pages = Math.max(1, Math.ceil(view.length / PER));
  if (page >= pages) page = pages - 1;
  const canEdit = cols.some(c => c.editable);
  $('tbl').tHead.innerHTML = '<tr>' + shown.map(c =>
    '<th data-k="' + esc(c.key) + '">' + esc(c.label) + (sortKey === c.key ? (sortDir > 0 ? ' ▲' : ' ▼') : '') + '</th>'
  ).join('') + (canEdit ? '<th class="editcol">Edit</th>' : '') + '</tr>';
  $('tbl').tHead.querySelectorAll('th[data-k]').forEach(th => th.onclick = () => {
    const k = th.dataset.k;
    if (sortKey === k) sortDir = -sortDir; else { sortKey = k; sortDir = 1; }
    apply();
  });
  const total = baseRows().length;
  const slice = view.slice(page * PER, page * PER + PER);
  const body = $('tbl').tBodies[0];
  if (!slice.length) {
    body.innerHTML = '<tr><td class="empty" colspan="' + Math.max(1, shown.length + (canEdit ? 1 : 0)) + '">' + (total ? 'No deals match.' : 'Nothing to show yet.') + '</td></tr>';
  } else {
    body.innerHTML = slice.map((r, ri) => '<tr>' + shown.map(c => {
      const v = r[c.key] || '';
      const num = (c.type === 'integer' || c.type === 'double') ? ' class="num"' : '';
      return '<td' + num + ' title="' + esc(v) + '">' + cellHtml(c, v, r) + '</td>';
    }).join('') + (canEdit ? '<td class="editcol"><button class="ghost sm" data-ri="' + (page * PER + ri) + '">✎ Edit</button></td>' : '') + '</tr>').join('');
  }
  $('count').textContent = view.length === total ? total.toLocaleString() + ' deals' : view.length.toLocaleString() + ' of ' + total.toLocaleString() + ' shown';
  $('pageinfo').textContent = 'Page ' + (page + 1) + ' of ' + pages;
  $('prev').disabled = page === 0;
  $('next').disabled = page >= pages - 1;
}

/* ---------- edit popup ---------- */
let editRow = null, editInit = {};
function toast(msg) {
  const t = $('toast');
  t.textContent = msg; t.hidden = false;
  clearTimeout(toast.t); toast.t = setTimeout(() => { t.hidden = true; }, 3200);
}
function isModalOpen() { return !$('modal').hidden; }
function closeModal() { $('modal').hidden = true; document.body.style.overflow = ''; editRow = null; }
function fieldKind(c) {
  if (c.options && c.options.length) return 'select';
  if (c.type === 'boolean') return 'bool';
  if (c.type === 'date') return 'date';
  if (c.type === 'datetime') return 'datetime';
  if (c.type === 'integer' || c.type === 'double') return 'number';
  return 'text';
}
function editInputHtml(c, v) {
  const k = esc(c.key), kind = fieldKind(c);
  if (kind === 'select') {
    const cur = (c.options.find(o => o.name === v) || {}).id;
    let init = cur !== undefined ? cur : (v ? '__cur' : '');
    let h = '<select data-k="' + k + '" data-kind="select" data-init="' + esc(init) + '">';
    if (c.key !== 'STAGE_ID') h += '<option value=""' + (init === '' ? ' selected' : '') + '>(empty)</option>';
    if (init === '__cur') h += '<option value="__cur" selected>' + esc(v) + ' (current)</option>';
    h += c.options.map(o => '<option value="' + esc(o.id) + '"' + (init === o.id ? ' selected' : '') + '>' + esc(o.name) + '</option>').join('');
    return h + '</select>';
  }
  if (kind === 'bool') {
    return '<select data-k="' + k + '" data-kind="bool" data-init="' + esc(v) + '">' + ['', 'Yes', 'No'].map(o => '<option value="' + o + '"' + (o === v ? ' selected' : '') + '>' + (o || '(empty)') + '</option>').join('') + '</select>';
  }
  if (kind === 'date') return '<input type="date" data-k="' + k + '" data-kind="date" data-init="' + esc(String(v).slice(0, 10)) + '" value="' + esc(String(v).slice(0, 10)) + '">';
  if (kind === 'datetime') { const dv = String(v).replace(' ', 'T').slice(0, 16); return '<input type="datetime-local" data-k="' + k + '" data-kind="datetime" data-init="' + esc(dv) + '" value="' + esc(dv) + '">'; }
  if (kind === 'number') return '<input type="number" step="any" data-k="' + k + '" data-kind="number" data-init="' + esc(v) + '" value="' + esc(v) + '">';
  if (String(v).length > 60 || /\n/.test(v)) return '<textarea data-k="' + k + '" data-kind="text" data-init="' + esc(v) + '">' + esc(v) + '</textarea>';
  return '<input type="text" data-k="' + k + '" data-kind="text" data-init="' + esc(v) + '" value="' + esc(v) + '">';
}
function collectChanges() {
  const ch = {};
  $('mBody').querySelectorAll('[data-k]').forEach(el => {
    const cur = el.value;
    if (cur === el.dataset.init || (el.dataset.kind === 'select' && cur === '__cur')) return;
    if (el.dataset.def && el.dataset.init === '' && cur === el.dataset.def) return;
    ch[el.dataset.k] = cur;
  });
  return ch;
}
// Fields showing a default (Semester status "Not Paid") that Bitrix24 does not have yet. Saved together with any other save.
function collectDefaults() {
  const ch = {};
  $('mBody').querySelectorAll('[data-def]').forEach(el => { if (el.dataset.init === '' && el.value === el.dataset.def) ch[el.dataset.k] = el.value; });
  return ch;
}
function refreshEditState() {
  const ch = collectChanges(), n = Object.keys(ch).length;
  const cmEl = $('mCmNew'), byEl = $('mCmBy'), fu = colByRole('fuupd');
  const need = [];
  const contactOnly = n > 0 && Object.keys(ch).every(k => k === '_NAME' || k === '_PHONE' || k === '_EMAIL') && !(cmEl && cmEl.value.trim() !== '');
  if (n === 0 && !(cmEl && cmEl.value.trim() !== '')) need.push('a change');
  if (!contactOnly) {
    if (cmEl && cmEl.value.trim() === '') need.push('a comment');
    if (byEl && byEl.value.trim() === '') need.push('your name');
    if (fu && !(fu.key in ch && String(ch[fu.key]).trim() !== '')) need.push('a new Next Follow-up date');
  }
  $('mBody').querySelectorAll('.fld.ed').forEach(f => { const k = f.querySelector('[data-k]'); if (k) f.classList.toggle('chg', k.dataset.k in ch); });
  const fuEl = fu ? $('mBody').querySelector('[data-k="' + fu.key + '"]') : null;
  [[cmEl, 'a comment'], [byEl, 'your name'], [fuEl, 'a new Next Follow-up date']].forEach(p => { if (p[0] && p[0].closest('.fld')) p[0].closest('.fld').classList.toggle('need', need.indexOf(p[1]) >= 0); });
  $('mSave').disabled = need.length > 0;
  $('mHint').textContent = need[0] === 'a change' ? 'Change a value to enable Save. Fixing only the name, phone or email needs no comment.' : need.length ? 'To save, please add: ' + need.join(', ') + '.' : (n ? n + ' field' + (n > 1 ? 's' : '') + ' changed + ' : '') + 'Ready. Save writes it to Bitrix24 and this page.';
}
const colByRole = id => id.charAt(0) === '_' ? cols.find(c => c.key === id) : cols.find(c => c.role === id);
const EDIT_SECS = [
  {t: 'Deal info', f: ['_NAME', '_PHONE', '_EMAIL']},
  {t: 'University and programme', f: ['uni', 'program', 'batch', 'stage', 'roll', 'team', 'sem', 'adm', 'reg', 'mode', 'lead', 'docstat', 'lastpay', 'nextpay']},
  {t: 'Comments and follow-up', fu: true},
  {t: 'Semester fees', f: ['semstat1', 'semstat2', 'semstat3', 'semstat4', 'semstat5', 'semstat6'], notPaid: true},
  {t: 'Reference', f: ['refhas', 'refadm']}
];
function fldHtml(c, r, notPaid) {
  const v = r[c.key] || '';
  if (!c.editable || (SCOPE && c.role === 'team')) return '<div class="fld" data-label="' + esc(c.label.toLowerCase()) + '"><span class="lbl">' + esc(c.label) + ' <i class="ro">read only</i></span><div class="val">' + (v ? esc(v) : '<span class="dash">·</span>') + '</div></div>';
  let inp = editInputHtml(c, v);
  if (notPaid && v === '' && c.options) {
    const o = c.options.find(x => /^not\s*paid$/i.test(x.name));
    if (o) inp = inp.replace(' data-kind="select"', ' data-kind="select" data-def="' + esc(o.id) + '"');
  }
  const ph = c.key === '_PHONE' || c.key === '_EMAIL' ? ' <i class="ro">separate several with a comma</i>' : '';
  return '<div class="fld ed" data-label="' + esc(c.label.toLowerCase()) + '"><label>' + esc(c.label) + ph + '</label>' + inp + '</div>';
}
function openEdit(r) {
  track('edit_open', '');
  editRow = r;
  const name = r._NAME || r.TITLE || ('Deal ' + r.ID);
  $('mTitle').textContent = name;
  $('mSub').textContent = 'Deal #' + r.ID + ' · ' + uniOf(r) + (r.STAGE_ID ? ' · ' + r.STAGE_ID : '');
  $('mBanner').hidden = true;
  $('mFilter').value = '';
  let h = '';
  const used = {};
  EDIT_SECS.forEach(sec => {
    if (sec.fu) {
      const fu = colByRole('fuupd'), fv2 = colByRole('fuview'), hist = colByRole('cmhist');
      let by = ''; try { by = localStorage.getItem('pranav_cm_by') || ''; } catch (e) {}
      if (!by) by = ME;
      let L = '', R = '';
      if (cmSrc) {
        L += '<div class="fld wide ed" data-label="add a comment new comment"><label for="mCmNew">Comments</label><textarea id="mCmNew" rows="3" maxlength="1000" placeholder="Type the follow-up note here. It is saved to Bitrix24 with today\'s date and time."></textarea></div>' +
          '<div class="fld ed" data-label="comment by your name"><label for="mCmBy">Comment by (your name)</label><input type="text" id="mCmBy" maxlength="60" value="' + esc(by) + '" placeholder="e.g. Sahana A"></div>' +
          '<div class="fld" data-label="last updated on"><span class="lbl">Last updated on <i class="ro">read only</i></span><div class="val">' + (r._COMMENT_AT ? esc(r._COMMENT_AT) : '<span class="dash">·</span>') + '</div></div>';
        R += '<div class="fld wide" data-label="comment history"><span class="lbl">' + esc(hist ? hist.label : 'Comment History') + ' <i class="ro">read only</i></span><textarea class="hist" readonly>' + esc(r[cmSrc] || '') + '</textarea></div>';
        used[cmSrc] = 1;
      }
      if (fu) { L += fldHtml(fu, r); used[fu.key] = 1; }
      if (fv2) { R += fldHtml(fv2, r); used[fv2.key] = 1; }
      if (L || R) h += '<div class="msec">' + sec.t + '</div><div class="mtwo"><div class="mcol">' + L + '</div><div class="mcol">' + R + '</div></div>' +
        (cmSrc ? '<div class="sub" style="margin:6px 0 0">A new comment is added on top of the history as <b>comment | date time | your name</b>. Older comments are never changed.</div>' : '');
      return;
    }
    const cs = sec.f.map(colByRole).filter(Boolean);
    if (!cs.length) return;
    cs.forEach(c => { used[c.key] = 1; });
    h += '<div class="msec">' + sec.t + (sec.notPaid ? ' <i class="ro">empty means Not Paid</i>' : '') + '</div><div class="mgrid">' + cs.map(c => fldHtml(c, r, sec.notPaid)).join('') + '</div>';
  });
  $('mBody').innerHTML = h;
  $('mBody').querySelectorAll('[data-def]').forEach(el => { if (el.dataset.init === '') el.value = el.dataset.def; });
  $('mBody').querySelectorAll('[data-k]').forEach(el => { el.oninput = refreshEditState; el.onchange = refreshEditState; });
  if ($('mCmNew')) $('mCmNew').oninput = refreshEditState;
  if ($('mCmBy')) $('mCmBy').oninput = refreshEditState;
  $('mSave').disabled = true;
  $('mSave').textContent = 'Save to Bitrix24';
  $('mCancel').disabled = false;
  $('modal').hidden = false;
  document.body.style.overflow = 'hidden';
  refreshEditState();
  $('mBody').scrollTop = 0;
}
function applyRowUpdates(r, upd) {
  Object.keys(upd).forEach(k => { if (upd[k] === '') delete r[k]; else r[k] = upd[k]; });
  deriveRow(r);
  indexRow(r);
}
async function saveEdit() {
  const r = editRow;
  if (!r) return;
  const changes = collectChanges();
  const cmText = $('mCmNew') ? $('mCmNew').value.trim() : '', cmBy = $('mCmBy') ? $('mCmBy').value.trim() : '';
  if ($('mSave').disabled) return;
  if (cmText && cmBy) { try { localStorage.setItem('pranav_cm_by', cmBy); } catch (e) {} }
  if (!(Object.keys(changes).every(k => k === '_NAME' || k === '_PHONE' || k === '_EMAIL') && !cmText)) Object.assign(changes, collectDefaults());
  const original = {};
  Object.keys(changes).forEach(k => { original[k] = r[k] || ''; });
  $('mSave').disabled = true; $('mCancel').disabled = true;
  $('mSave').textContent = 'Saving…';
  $('mBanner').hidden = true;
  let resp, j = {};
  try {
    resp = await fetch('api.php?action=update', {method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-Token': CSRF, 'Content-Type': 'application/json'}, body: JSON.stringify({id: r.ID, changes: changes, original: original, comment: cmText, by: cmBy})});
    if (resp.status === 401) { location.reload(); return; }
    j = await resp.json().catch(() => ({}));
  } catch (e) {
    resp = null;
  }
  $('mCancel').disabled = false;
  $('mSave').textContent = 'Save to Bitrix24';
  if (resp && resp.ok && j.ok) {
    applyRowUpdates(r, j.updated || {});
    if (!(Object.keys(changes).every(k => k === '_NAME' || k === '_PHONE' || k === '_EMAIL') && !cmText)) TOUCHED[r.ID] = Date.now();
    closeModal();
    renderRoute(false);
    toast('Saved to Bitrix24 ✓');
    return;
  }
  let msg = !resp ? 'Could not reach the server. Nothing was saved.' : (j.error || ('Server error ' + resp.status + '. Nothing was saved.'));
  if (resp && resp.status === 409 && j.conflicts) {
    const parts = Object.keys(j.conflicts).map(k => j.conflicts[k].label + ' is now "' + (j.conflicts[k].live || '(empty)') + '"');
    msg += ' Changed in Bitrix24: ' + parts.join('; ') + '. The latest values are loaded, so please check and save again.';
    applyRowUpdates(r, j.fresh || {});
    renderRoute(false);
    openEdit(r);
  }
  $('mBanner').textContent = msg;
  $('mBanner').hidden = false;
  if (!isModalOpen()) return;
  refreshEditState();
}
$('tbl').tBodies[0].addEventListener('click', e => {
  const b = e.target.closest('button[data-ri]');
  if (!b) return;
  const r = view[+b.dataset.ri];
  if (r) openEdit(r);
});
$('mClose').onclick = closeModal;
$('mCancel').onclick = closeModal;
$('mSave').onclick = saveEdit;
$('modal').addEventListener('mousedown', e => { if (e.target === $('modal')) closeModal(); });
$('mFilter').oninput = () => {
  const t = $('mFilter').value.toLowerCase().trim();
  $('mBody').querySelectorAll('.fld').forEach(f => f.classList.toggle('hide', t !== '' && f.dataset.label.indexOf(t) < 0));
};

function csvCell(v) {
  v = String(v == null ? '' : v);
  if (/^[=@]/.test(v) || (/^[+-]/.test(v) && !/^[+-]?[\d\s()-]+$/.test(v))) v = "'" + v;
  return '"' + v.replace(/"/g, '""') + '"';
}

$('btnCsv').onclick = () => {
  const shown = shownCols();
  const lines = [shown.map(c => csvCell(c.label)).join(',')];
  view.forEach(r => lines.push(shown.map(c => csvCell(r[c.key] || '')).join(',')));
  const blob = new Blob(['﻿' + lines.join('\r\n')], {type: 'text/csv;charset=utf-8'});
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = 'pranav-' + (route.type === 'uni' ? route.name.replace(/[^A-Za-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'university' : 'master') + '-' + new Date().toISOString().slice(0, 10) + '.csv';
  document.body.appendChild(a);
  a.click();
  a.remove();
};

/* WhatsApp. Browser tabs cannot be reused: WhatsApp Web cuts the link between its tab and this page, so every chat opens a new tab.
   "Desktop app" mode opens the chat inside the WhatsApp app instead (no tab at all). Chosen per device from the account menu. */
const WA_KEY = 'pranav_wa_mode';
const waMode = () => { try { return localStorage.getItem(WA_KEY) === 'app' ? 'app' : 'web'; } catch (e) { return 'web'; } };
function toastWa() { const t = document.getElementById('updated'); if (!t) return; const o = t.textContent; t.textContent = waMode() === 'app' ? 'WhatsApp will open in the desktop app (install it from whatsapp.com/download)' : 'WhatsApp will open in a browser tab'; setTimeout(() => { if (t.textContent.indexOf('WhatsApp') === 0) t.textContent = o; }, 3500); }
function paintWaMode() { const el = document.getElementById('waMode'); if (el) el.textContent = 'WhatsApp opens in: ' + (waMode() === 'app' ? 'Desktop app' : 'Browser tab'); }
/* ---- WhatsApp message templates. {n} lead name, {u} university, {s} your name, {p} your phone. Edit the wording here. ---- */
const WA_TPL = [
  {id: 'connected', label: 'Connected', dot: '#16a34a', text:
"Dear {n},\n\nThank you for connecting with {u} Online today.\nFor any further assistance regarding your course or academics, please feel free to reach out to us.\nWe look forward to supporting you throughout your learning journey.\n\nRegards,\n{s} ({p})\nStudent Support SPOC\n{u} Online"},
  {id: 'rnr', label: 'RNR', dot: '#e11d48', text:
"Hello {n},\n\nGreetings from {u}! 👋\nI’m {s}, your SPOC for Student Support at {u}.\nI’ll be your go-to person for any academic, LMS, Payments, examination, assignment, or course-related queries.\nPlease feel free to reach out to me anytime. I’m always happy to help and support you throughout your learning journey. 😊\nLooking forward to connecting with you!\n\nBest Regards,\n{s} ({p})\nStudent Support SPOC\n{u}"},
  {id: 'alconn', label: 'Alumni Connected', dot: '#0ea5e9', text:
"Dear {n},\n\nGreetings from {u} Online!\nCongratulations on completing your course! We hope you had a great learning experience with us.\nWe would be happy to assist your friends or colleagues who may be interested in pursuing their higher education with {u} Online. Please feel free to share our details with them.\n\nBest Regards,\n{s} ({p})\nStudent Support SPOC\n{u} Online"},
  {id: 'alrnr', label: 'Alumni RNR', dot: '#f59e0b', text:
"Dear {n},\n\nGreetings from {u} Online!\nHope you’re doing well! We’d love to know how your learning experience was and how smoothly the course went for you.\nAlso, if you have any friends or colleagues interested in pursuing higher education, we’d be happy to guide them with {u} Online programs.\n\nBest Regards,\n{s} ({p})\nStudent Support SPOC\n{u} Online"}
];
// Signature = the logged-in person (Your profile: display name + phone). If those are empty, the wording from the shared Word file is used.
const waSender = () => ({s: (PROFILE.name || '').trim() || 'Tanuja', p: (PROFILE.phone || '').trim() || '70900 31900'});
function waText(tpl, r) {
  const sg = waSender(), u = (typeof uniOf === 'function' ? uniOf(r) : '') || '', uni = (!u || u === UNASSIGNED) ? 'our university' : u;
  const nm = String(r._NAME || r.TITLE || '').replace(/\s+/g, ' ').trim() || 'Student';
  return tpl.text.replace(/\{n\}/g, nm).replace(/\{u\}/g, uni).replace(/\{s\}/g, sg.s).replace(/\{p\}/g, sg.p);
}
function waGo(url, app) {
  if (app) { location.href = url; return; }
  const w = window.open(url, 'pranav_whatsapp');
  if (w) { try { w.focus(); } catch (x) {} } else location.href = url;
}
let waPop = null;
function waClose() { if (waPop) { waPop.remove(); waPop = null; } }
function waOpenMenu(a) {
  waClose();
  const r = allRows.find(x => String(x.ID) === String(a.dataset.id)); if (!r) { waGo(a.href, waMode() === 'app'); return; }
  const nm = String(r._NAME || r.TITLE || 'this lead').trim(), uni = (typeof uniOf === 'function' ? uniOf(r) : '');
  const d = document.createElement('div'); d.className = 'wapop'; d.setAttribute('role', 'dialog');
  d.innerHTML = '<div class="wph"><b>Message ' + esc(nm) + '</b><small>' + esc(uni && uni !== UNASSIGNED ? uni : 'No university set') + ' · +' + esc(a.dataset.ph) + '</small></div>' +
    WA_TPL.map(t => '<button type="button" data-t="' + t.id + '"><i style="background:' + t.dot + '"></i>' + esc(t.label) + '</button>').join('') +
    '<div class="wpf">The message is only typed into the chat box. You press send yourself.</div>';
  document.body.appendChild(d); waPop = d;
  const b = a.getBoundingClientRect(), w = d.offsetWidth, h = d.offsetHeight;
  d.style.left = Math.max(8, Math.min(innerWidth - w - 8, b.left - 20)) + 'px';
  d.style.top = (b.bottom + h + 12 > innerHeight ? Math.max(8, b.top - h - 8) : b.bottom + 8) + 'px';
  d.onclick = e => {
    const x = e.target.closest('button[data-t]'); if (!x) return;
    const t = WA_TPL.find(q => q.id === x.dataset.t), txt = encodeURIComponent(waText(t, r));
    waClose();
    if (waMode() === 'app') waGo('whatsapp://send?phone=' + a.dataset.ph + '&text=' + txt, true);
    else waGo('https://wa.me/' + a.dataset.ph + '?text=' + txt, false);
  };
}
document.addEventListener('click', e => {
  const a = e.target.closest('a.wapp');
  if (a) { e.preventDefault(); e.stopPropagation(); waOpenMenu(a); return; }
  if (waPop && !e.target.closest('.wapop')) waClose();
}, true);
document.addEventListener('keydown', e => { if (e.key === 'Escape') waClose(); });
window.addEventListener('scroll', waClose, true);
window.addEventListener('hashchange', waClose);
/* ---------- saved views (kept per login, so they follow you to any device) ---------- */
function curViewHash() {
  const h = location.hash || '#/', i = h.indexOf('?'), base = i < 0 ? h : h.slice(0, i), sp = new URLSearchParams(i < 0 ? '' : h.slice(i + 1));
  if (stage) sp.set('s', stage); else sp.delete('s');
  if (query) sp.set('q', query); else sp.delete('q');
  const q = sp.toString(); return base + (q ? '?' + q : '');
}
async function saveViews(list) {
  PROFILE.views = list; paintViews();
  try { await fetch('api.php?action=views_save', {method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-Token': CSRF, 'Content-Type': 'application/json'}, body: JSON.stringify({views: list})}); } catch (e) {}
}
function paintViews() {
  const el = $('svNav'); if (!el) return;
  const v = Array.isArray(PROFILE.views) ? PROFILE.views : [];
  el.innerHTML = v.length ? '<div class="svh t">Saved views</div>' + v.map((x, i) => '<div class="svr"><a class="subnav" href="' + esc(x.h) + '" title="' + esc(x.n) + '"><i>★</i><span class="t">' + esc(x.n) + '</span></a><button type="button" class="svx" data-i="' + i + '" title="Remove" aria-label="Remove ' + esc(x.n) + '">✕</button></div>').join('') : '';
}
$('svNav').onclick = e => { const b = e.target.closest('button[data-i]'); if (b) saveViews((PROFILE.views || []).filter((x, i) => i !== +b.dataset.i)); };
$('btnSv').onclick = () => {
  if ($('svBox')) { $('svBox').remove(); return; }
  const box = document.createElement('span'); box.id = 'svBox';
  box.innerHTML = '<input id="svName" maxlength="40" placeholder="Name this view" autocomplete="off"><button type="button" class="ghost" id="svOk">Save</button><button type="button" class="ghost" id="svNo">Cancel</button>';
  $('btnSv').after(box); $('svName').focus();
  const done = () => box.remove();
  const ok = () => {
    const n = $('svName').value.trim(); if (!n) { $('svName').focus(); return; }
    const list = (PROFILE.views || []).filter(x => x.n.toLowerCase() !== n.toLowerCase()); list.push({n: n, h: curViewHash()});
    if (list.length > 20) list.shift(); saveViews(list); done();
  };
  $('svNo').onclick = done; $('svOk').onclick = ok; $('svName').onkeydown = e => { if (e.key === 'Enter') ok(); else if (e.key === 'Escape') done(); };
};
$('uq').oninput = () => { if (route.type === 'home') renderHome(); };
window.addEventListener('hashchange', () => { if (cols.length || rows.length) renderRoute(true); });
$('q').oninput = () => { query = $('q').value; page = 0; apply(); };
$('prev').onclick = () => { if (page > 0) { page--; renderTable(); } };
$('next').onclick = () => { page++; renderTable(); };
$('colsDefault').onclick = () => { visible = cols.filter(c => c.default).map(c => c.key); lsSet(visible); renderColsList(); page = 0; apply(); };
$('colsAll').onclick = () => { visible = cols.map(c => c.key); lsSet(visible); renderColsList(); page = 0; apply(); };


/* refresh progress bar */
let rfTimer = null;
const rfPost = 'api.php?action=progress';
function rfStart(manual) {
  const bar = $('rfbar'), fill = bar.firstElementChild;
  bar.hidden = false; fill.style.width = '2%';
  clearInterval(rfTimer);
  const tick = async () => {
    try {
      const r = await fetch(rfPost, {credentials: 'same-origin', cache: 'no-store'});
      if (!r.ok) return;
      const p = await r.json();
      const pct = Math.max(2, Math.min(99, p.pct | 0));
      fill.style.width = pct + '%';
      if (manual) {
        $('btnRefresh').textContent = '↻ Refreshing ' + pct + '%';
        $('updated').textContent = (p.label ? p.label + ' · ' : '') + pct + '% done';
      } else $('updated').textContent = 'Updating… ' + pct + '%' + (p.label ? ' · ' + p.label : '');
    } catch (e) {}
  };
  fetch(rfPost + '&reset=1', {method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-Token': CSRF}}).catch(() => {}).then(() => { if (!bar.hidden) { rfTimer = setInterval(tick, 700); tick(); } });
}
function rfStop() {
  clearInterval(rfTimer); rfTimer = null;
  const bar = $('rfbar'), fill = bar.firstElementChild;
  fill.style.width = '100%';
  setTimeout(() => { bar.hidden = true; fill.style.width = '0'; }, 600);
}

$('btnRefresh').onclick = async () => {
  const btn = $('btnRefresh');
  btn.disabled = true; rfBusy = true;
  btn.textContent = '↻ Refreshing…';
  $('updated').textContent = 'Fetching from Bitrix24…';
  let problem = '';
  rfStart(true);
  try {
    const r = await fetch('api.php?action=refresh', {method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-Token': CSRF}});
    if (r.status === 401) { location.reload(); return; }
    const j = await r.json().catch(() => ({}));
    if (!r.ok || !j.ok) problem = j.error || ('Server error ' + r.status);
  } catch (e) {
    problem = 'Could not reach the server.';
  }
  rfStop();
  try { await load(); } catch (e) { problem = problem || 'Could not load the data.'; }
  if (problem) showBanner('Refresh failed: ' + problem);
  rfBusy = false; showUpdated();
  btn.disabled = false;
  btn.textContent = '↻ Refresh data';
};

/* auto refresh every 5 minutes */
const AUTO_MS = 5 * 60000;
let lastAuto = 0;
async function autoRefresh() {
  if (rfBusy) return;
  rfBusy = true; lastAuto = Date.now();
  rfStart(false);
  try {
    await fetch('api.php?action=refresh&auto=1', {method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-Token': CSRF}});
  } catch (e) {}
  rfStop();
  rfBusy = false;
  try { await load(true); } catch (e) {}
}
function autoDue() {
  if (rfBusy || document.hidden || isModalOpen() || !fetchedAt) return false;
  const t = (document.activeElement || {}).tagName || '';
  if (/INPUT|TEXTAREA|SELECT/.test(t)) return false;
  return Date.now() - fetchedAt.getTime() >= AUTO_MS && Date.now() - lastAuto >= AUTO_MS;
}
setInterval(() => { showUpdated(); if (autoDue()) autoRefresh(); }, 30000);
document.addEventListener('visibilitychange', () => { if (!document.hidden) { showUpdated(); if (autoDue()) autoRefresh(); } });

document.addEventListener('keydown', e => { if (e.key === 'Escape' && isModalOpen()) { closeModal(); return; }
  if (e.key === 'Escape' && route.sheet && !/INPUT|TEXTAREA/.test((e.target || {}).tagName || '')) location.hash = route.rb || hashFor(scopeName(), null, false); });
document.addEventListener('click', e => { const d = $('colsBox'); if (d.open && !d.contains(e.target)) d.open = false; });
load().catch(() => { $('updated').textContent = 'Could not load the data.'; });

// count-up for KPI / tile numbers (runs once per newly rendered element)
(function () {
  if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  function run(el) {
    if (el.__cu || el.children.length) return;
    const t = el.textContent.trim();
    if (!/^[\d,]+$/.test(t)) return;
    const end = parseInt(t.replace(/,/g, ''), 10);
    if (!isFinite(end) || end < 10) return;
    el.__cu = 1;
    const fmt = t.indexOf(',') >= 0 ? (n => n.toLocaleString('en-IN')) : (n => String(n));
    const t0 = performance.now(), dur = 700;
    (function step(now) {
      const k = Math.min(1, (now - t0) / dur), e = 1 - Math.pow(1 - k, 3);
      el.textContent = fmt(k < 1 ? Math.round(end * e) : end);
      if (k < 1) requestAnimationFrame(step);
    })(t0);
  }
  new MutationObserver(ms => {
    ms.forEach(m => m.addedNodes.forEach(n => {
      if (n.nodeType !== 1) return;
      if (n.matches && n.matches('.kpi b,a.tile b')) run(n);
      if (n.querySelectorAll) n.querySelectorAll('.kpi b,a.tile b').forEach(run);
    }));
  }).observe(document.body, {childList: true, subtree: true});
})();
</script>
<script>
(function () {
  var h = new Date().getHours(), hi = h < 12 ? 'Good morning,' : h < 17 ? 'Good afternoon,' : 'Good evening,';
  var el = document.getElementById('heroHi'), nm = document.getElementById('heroName');
  var first = String((typeof PROFILE !== 'undefined' && PROFILE.name) || ME || '').trim().split(/[_\s.\-0-9]+/)[0] || 'there'; first = first.charAt(0).toUpperCase() + first.slice(1);
  if (el) el.textContent = hi; if (nm) nm.textContent = first;
  document.addEventListener('keydown', function (e) {
    if ((e.ctrlKey || e.metaKey) && String(e.key).toLowerCase() === 'k') { e.preventDefault(); var g = document.getElementById('gsearch'); if (g) { g.focus(); g.select(); } }
  });
  var hero = document.getElementById('hero'), sc = document.getElementById('hscene');
  if (hero && sc && !(window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches)) hero.addEventListener('mousemove', function (e) { var r = hero.getBoundingClientRect(); sc.style.setProperty('--hx', ((e.clientX - r.left) / r.width - .5).toFixed(3)); sc.style.setProperty('--hy', ((e.clientY - r.top) / r.height - .5).toFixed(3)); });
})();
</script>
<script>
(function () {
  const g = id => document.getElementById(id);
  const COLORS = ['#21bbb0', '#e11d48', '#0b8a4f', '#e8590c', '#0ea5e9', '#ea8b42', '#ca8a04', '#475569'];
  const role = SCOPE ? 'Team ' + SCOPE : 'Admin · All data';
  const disp = () => PROFILE.name || String(ME).replace(/_/g, ' ');
  function paint() {
    const n = disp(), ini = (n.charAt(0) || '?').toUpperCase(), c = PROFILE.color;
    ['uAv', 'uAv2'].forEach(id => { const e = g(id); if (!e) return; if (AV[ME]) { e.innerHTML = avImg(ME); return; } e.textContent = ini; if (c) e.style.setProperty('--avc', 'linear-gradient(135deg,' + c + ',' + c + 'aa)'); else e.style.removeProperty('--avc'); });
    g('uNm').textContent = n; g('uSc').textContent = role; g('uNm2').textContent = n; g('uUn2').textContent = '@' + ME + ' · ' + role;
    const hn = g('heroName'); if (hn && PROFILE.name) { const f = PROFILE.name.trim().split(/\s+/)[0]; hn.textContent = f.charAt(0).toUpperCase() + f.slice(1); }
  }
  paint();
  const btn = g('uBtn'), dd = g('uDrop');
  const shut = () => { dd.hidden = true; btn.setAttribute('aria-expanded', 'false'); };
  btn.onclick = e => { e.stopPropagation(); const o = dd.hidden; dd.hidden = !o; btn.setAttribute('aria-expanded', o ? 'true' : 'false'); };
  document.addEventListener('click', e => { if (!e.target.closest('#umenu')) shut(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { shut(); closeOv(); } });
  dd.addEventListener('click', e => {
    const it = e.target.closest('[data-a]'); if (!it) return;
    const a = it.dataset.a; shut();
    if (a === 'profile') openProfile(); else if (a === 'pwd') openPwd(); else if (a === 'usage') openUsage();
    else if (a === 'wa') { try { localStorage.setItem(WA_KEY, waMode() === 'app' ? 'web' : 'app'); } catch (x) {} paintWaMode(); toastWa(); }
    else if (a === 'monkey') { if (window.__monkeyLife) window.__monkeyLife.toggle(); }
    else if (a === 'wake') { if (window.__ppWake) window.__ppWake.toggle(); }
    else if (a === 'sound') { if (window.PPSound) window.PPSound.toggle(); }
    else if (a === 'voice') { if (window.PPSound) window.PPSound.nextVoice(); }
    else if (a === 'search') { const s = g('gsearch'); if (s) { s.focus(); s.select(); } }
    else if (a === 'refresh') g('btnRefresh').click();
  });
  const ov = g('pfOv'), box = g('pfBox');
  function closeOv() { ov.hidden = true; box.innerHTML = ''; box.style.width = ''; }
  ov.addEventListener('mousedown', e => { if (e.target === ov) closeOv(); });
  async function post(action, body) {
    const r = await fetch('api.php?action=' + action, {method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-Token': CSRF, 'Content-Type': 'application/json'}, body: JSON.stringify(body)});
    if (r.status === 401) { location.reload(); return {ok: false, error: 'Please log in again.'}; }
    let j = {}; try { j = await r.json(); } catch (e) {}
    return r.ok && j.ok ? {ok: true, data: j} : {ok: false, error: j.error || 'Something went wrong. Please try again.'};
  }
  function openProfile() {
    let color = PROFILE.color || COLORS[0];
    box.innerHTML = '<h2>Your profile</h2><p class="sub">This is how you appear on the dashboard.</p>' +
      '<div class="pfp"><span class="av" id="pfAv"></span><div><b id="pfNm"></b><small>@' + esc(ME) + ' · ' + esc(role) + '</small></div></div>' +
      '<div class="pff">Profile photo<div class="pfph"><button type="button" class="ghost" id="pfPick">Upload photo</button><button type="button" class="ghost" id="pfRm"' + (AV[ME] ? '' : ' hidden') + '>Remove photo</button><input type="file" id="pfFile" accept="image/*" hidden></div><div class="pfm" id="pfPm"></div></div>' +
      '<label class="pff">Display name<input id="pfName" maxlength="40" placeholder="' + esc(String(ME).replace(/_/g, ' ')) + '" value="' + esc(PROFILE.name) + '"></label>' +
      '<label class="pff">Phone<input id="pfPhone" maxlength="20" inputmode="tel" placeholder="Optional" value="' + esc(PROFILE.phone) + '"></label>' +
      '<label class="pff">Username<input value="' + esc(ME) + '" readonly></label>' +
      '<div class="pff">Avatar colour<div class="sw" id="pfSw">' + COLORS.map(c => '<button type="button" style="--c:' + c + '" data-c="' + c + '" aria-label="' + c + '"></button>').join('') + '</div></div>' +
      '<div class="pfm" id="pfMsg"></div><div class="pfa"><button type="button" class="ghost" id="pfX">Cancel</button><button type="button" id="pfOk">Save profile</button></div>';
    ov.hidden = false;
    const prev = () => { const n = g('pfName').value.trim() || String(ME).replace(/_/g, ' '); g('pfNm').textContent = n; const a = g('pfAv'); if (AV[ME]) a.innerHTML = avImg(ME); else a.textContent = n.charAt(0).toUpperCase(); a.style.setProperty('--avc', 'linear-gradient(135deg,' + color + ',' + color + 'aa)'); box.querySelectorAll('#pfSw button').forEach(b => b.classList.toggle('on', b.dataset.c === color)); };
    prev();
    g('pfName').oninput = prev;
    const pm = g('pfPm'), refresh = () => { paint(); prev(); g('pfRm').hidden = !AV[ME]; try { if (typeof drawRecent === 'function') drawRecent(); if (g('actTable') && typeof renderActivityRows === 'function') renderActivityRows(); } catch (e) {} };
    const sendAv = async (action, body) => {
      const r = await fetch('api.php?action=' + action, {method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-Token': CSRF, 'Content-Type': 'application/octet-stream'}, body: body || ''});
      let j = {}; try { j = await r.json(); } catch (e) {}
      if (r.status === 401) { location.reload(); return null; }
      return r.ok && j.ok ? j : {error: j.error || 'Something went wrong. Please try again.'};
    };
    const toJpeg = file => new Promise((res, rej) => {
      const fr = new FileReader(), im = new Image();
      im.onload = () => { try {
        const s = Math.min(im.naturalWidth, im.naturalHeight), c = document.createElement('canvas'); c.width = c.height = 256;
        const x = c.getContext('2d'); x.fillStyle = '#fff'; x.fillRect(0, 0, 256, 256);
        x.drawImage(im, (im.naturalWidth - s) / 2, (im.naturalHeight - s) / 2, s, s, 0, 0, 256, 256);
        c.toBlob(b => b ? res(b) : rej(), 'image/jpeg', .88);
      } catch (e) { rej(e); } };
      im.onerror = () => rej(); fr.onerror = () => rej(); fr.onload = () => { im.src = fr.result; }; fr.readAsDataURL(file);
    });
    g('pfPick').onclick = () => g('pfFile').click();
    g('pfFile').onchange = async () => {
      const f = g('pfFile').files[0]; g('pfFile').value = ''; if (!f) return;
      if (!/^image\//.test(f.type)) { pm.className = 'pfm err'; pm.textContent = 'Please choose an image file.'; return; }
      pm.className = 'pfm'; pm.textContent = 'Uploading…';
      let blob; try { blob = await toJpeg(f); } catch (e) { pm.className = 'pfm err'; pm.textContent = 'That image could not be read. Try a JPG or PNG.'; return; }
      const j = await sendAv('avatar_save', blob); if (!j) return;
      if (j.error) { pm.className = 'pfm err'; pm.textContent = j.error; return; }
      AV[ME] = j.av; PROFILE.av = j.av; pm.className = 'pfm ok'; pm.textContent = 'Photo updated.'; refresh();
    };
    g('pfRm').onclick = async () => {
      pm.className = 'pfm'; pm.textContent = 'Removing…';
      const j = await sendAv('avatar_del'); if (!j) return;
      if (j.error) { pm.className = 'pfm err'; pm.textContent = j.error; return; }
      delete AV[ME]; PROFILE.av = 0; pm.className = 'pfm ok'; pm.textContent = 'Photo removed.'; refresh();
    };
    g('pfSw').onclick = e => { const b = e.target.closest('button[data-c]'); if (b) { color = b.dataset.c; prev(); } };
    g('pfX').onclick = closeOv;
    g('pfOk').onclick = async () => {
      const m = g('pfMsg'), b = g('pfOk'); b.disabled = true; m.className = 'pfm'; m.textContent = 'Saving…';
      const r = await post('profile_save', {name: g('pfName').value, phone: g('pfPhone').value, color: color});
      b.disabled = false;
      if (!r.ok) { m.className = 'pfm err'; m.textContent = r.error; return; }
      Object.assign(PROFILE, r.data.profile); paint(); m.className = 'pfm ok'; m.textContent = 'Saved.'; setTimeout(closeOv, 600);
    };
    g('pfName').focus();
  }
  async function openUsage() {
    box.innerHTML = '<h2>Usage · last 30 days</h2><p class="sub">Who is actually using the dashboard, and what for.</p><div id="usB"><div class="pfh">Loading…</div></div><div class="pfa"><button type="button" class="ghost" id="pfX">Close</button></div>';
    ov.hidden = false; g('pfX').onclick = closeOv; box.style.width = 'min(760px,100%)';
    let j = {}; try { j = await (await fetch('api.php?action=usage', {credentials: 'same-origin'})).json(); } catch (e) {}
    mergeAV(j);
    if (!j.ok) { g('usB').innerHTML = '<div class="pfm err">' + esc(j.error || 'Could not load usage.') + '</div>'; return; }
    const ago = at => { if (!at) return 'never'; const m = Math.round((Date.now() - new Date(at).getTime()) / 60000); return m < 2 ? 'just now' : m < 60 ? m + ' min ago' : m < 1440 ? Math.round(m / 60) + ' h ago' : Math.round(m / 1440) + ' d ago'; };
    const T = j.totals || {};
    let h = '<h3 class="ush" style="margin-top:4px">Data health</h3><div class="pfh">Compares what the dashboard shows with Bitrix24 right now.</div><div class="hcheck" id="hcOut"><button type="button" class="ghost" id="hcGo">Check against Bitrix24 now</button></div>';
    h += '<div class="usg">' + Object.keys(j.features).map(k => '<div><b>' + ((T[k] || {}).d7 || 0) + '</b><small>' + esc(j.features[k]) + ' · 7 days</small><em>' + ((T[k] || {}).d30 || 0) + ' in 30 days</em></div>').join('') + '</div>';
    h += '<h3 class="ush">People</h3><div class="ust"><table><tr><th>Login</th><th>Last seen</th><th>Logins</th><th>Views</th><th>Bot</th><th>Search</th><th>Edits</th></tr>' + j.users.map(u => '<tr class="' + (u.last ? '' : 'dead') + '"><td>' + (AV[u.user] ? '<img class="av2" src="' + avUrl(u.user) + '" alt="">' : '') + esc(u.user) + '</td><td>' + esc(ago(u.last)) + '</td><td>' + u.logins + '</td><td>' + u.view + '</td><td>' + u.bot + '</td><td>' + u.search + '</td><td>' + u.edits + '</td></tr>').join('') + '</table></div>';
    const pg = Object.keys(j.pages); if (pg.length) h += '<h3 class="ush">Most-visited pages</h3><div class="ust"><table>' + pg.map(k => '<tr><td>' + esc(k) + '</td><td>' + j.pages[k] + '</td></tr>').join('') + '</table></div>';
    const ms = Object.keys(j.misses); h += '<h3 class="ush">Questions the bot could not answer</h3>' + (ms.length ? '<div class="ust"><table>' + ms.map(k => '<tr><td>' + esc(k) + '</td><td>' + j.misses[k] + '×</td></tr>').join('') + '</table></div>' : '<div class="pfh">None yet.</div>');
    const mx = Math.max.apply(null, [1].concat(j.dau.map(d => d.n)));
    if (j.dau.length) h += '<h3 class="ush">People per day</h3><div class="usd">' + j.dau.map(d => '<span title="' + esc(d.day) + ': ' + d.n + '"><i style="height:' + Math.round(d.n / mx * 100) + '%"></i><small>' + esc(d.day.slice(8)) + '</small></span>').join('') + '</div>';
    g('usB').innerHTML = h;
    g('hcGo').onclick = async () => {
      const o = g('hcOut'); o.className = 'hcheck'; o.textContent = 'Checking Bitrix24…';
      let r = {}; try { r = await (await fetch('api.php?action=reconcile', {credentials: 'same-origin'})).json(); } catch (e) {}
      if (!r.ok) { o.className = 'hcheck bad'; o.textContent = r.error || 'Could not check.'; return; }
      const x = r.r, f = n => Number(n).toLocaleString();
      o.className = 'hcheck ' + (x.ok ? 'good' : 'bad');
      o.innerHTML = (x.ok ? '✔ Matches Bitrix24: <b>' + f(x.live) + '</b> deals.' : '⚠ Dashboard has <b>' + f(x.cached) + '</b> deals, Bitrix24 has <b>' + f(x.live) + '</b> (newest deal ID ' + x.cached_max + ' vs ' + x.live_max + '). Press Refresh data.') + '<br><small>' + f(x.changed_since) + ' deal(s) changed in Bitrix24 since the last refresh (' + esc(String(x.fetched_at).replace('T', ' ').slice(0, 16)) + ').</small>';
    };
  }
  function strength(v) { let s = 0; if (v.length >= 8) s++; if (v.length >= 12) s++; if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++; if (/\d/.test(v)) s++; if (/[^A-Za-z0-9]/.test(v)) s++; return s; }
  function openPwd() {
    box.innerHTML = '<h2>Change password</h2><p class="sub">Use at least 8 characters. After this, your old password stops working.</p>' +
      '<label class="pff">Current password<input id="pwCur" type="password" autocomplete="current-password"></label>' +
      '<label class="pff">New password<input id="pwNew" type="password" autocomplete="new-password"></label><div class="pst"><i id="pwBar"></i></div><div class="pfh" id="pwHint">Mix upper and lower case, numbers and symbols.</div>' +
      '<label class="pff">Repeat new password<input id="pwRep" type="password" autocomplete="new-password"></label>' +
      '<div class="pfm" id="pfMsg"></div><div class="pfa"><button type="button" class="ghost" id="pfX">Cancel</button><button type="button" id="pfOk">Update password</button></div>';
    ov.hidden = false;
    g('pwNew').oninput = () => { const v = g('pwNew').value, k = strength(v); const b = g('pwBar'); b.style.width = (v ? Math.max(12, k * 20) : 0) + '%'; b.style.background = k <= 1 ? '#e11d48' : k <= 3 ? '#f59e0b' : '#0b8a4f'; g('pwHint').textContent = !v ? 'Mix upper and lower case, numbers and symbols.' : k <= 1 ? 'Weak' : k <= 3 ? 'Okay' : 'Strong'; };
    g('pfX').onclick = closeOv;
    g('pfOk').onclick = async () => {
      const m = g('pfMsg'), b = g('pfOk'), cur = g('pwCur').value, nw = g('pwNew').value;
      m.className = 'pfm err';
      if (!cur) { m.textContent = 'Enter your current password.'; return; }
      if (nw.length < 8) { m.textContent = 'The new password needs at least 8 characters.'; return; }
      if (nw !== g('pwRep').value) { m.textContent = 'The two new passwords do not match.'; return; }
      b.disabled = true; m.className = 'pfm'; m.textContent = 'Updating…';
      const r = await post('password_change', {current: cur, next: nw});
      b.disabled = false;
      if (!r.ok) { m.className = 'pfm err'; m.textContent = r.error; return; }
      m.className = 'pfm ok'; m.textContent = 'Password updated.'; setTimeout(closeOv, 900);
    };
    g('pwCur').focus();
  }
})();
</script>
<script>
(function () {
  const g = id => document.getElementById(id);
  const ov = g('pkOv'), box = g('pkBox');
  let mode = '';
  function close() { ov.hidden = true; box.innerHTML = ''; mode = ''; g('navP').setAttribute('aria-expanded', 'false'); g('navU').setAttribute('aria-expanded', 'false'); g('navR').setAttribute('aria-expanded', 'false'); }
  ov.addEventListener('mousedown', e => { if (e.target === ov) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !ov.hidden) close(); });
  window.addEventListener('hashchange', () => { if (!ov.hidden) close(); });
  box.addEventListener('click', e => { if (e.target.closest('.pkx')) close(); else if (e.target.closest('a[href]')) setTimeout(close, 0); });
  function shell(title, icon, sub) {
    box.innerHTML = '<div class="pkh"><h2>' + icon + ' ' + esc(title) + '</h2><p>' + sub + '</p><button type="button" class="pkx" aria-label="Close"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button></div>' +
      '<div class="pkt"><input id="pkQ" type="search" placeholder="Type to search…" autocomplete="off">' + (mode === 'u' ? '<select id="pkS"><option value="az">A to Z</option><option value="deals">Most deals</option><option value="stale">Most overdue</option></select>' : '') + '</div>' +
      '<div class="pkg" id="pkG"></div><div class="pkf" id="pkF" hidden></div>';
    box.classList.toggle('lst', mode === 'u');
    ov.hidden = false;
  }
  function uniDraw() {
    const st = uniStats(), q = g('pkQ').value.toLowerCase().trim(), so = g('pkS').value, total = rows.length;
    const curU = route.type === 'uni' ? route.name.split(MS) : [];
    let names = Object.keys(st).filter(n => n.toLowerCase().indexOf(q) >= 0);
    names.sort((a, b) => so === 'deals' ? st[b].n - st[a].n || a.localeCompare(b) : so === 'stale' ? st[b].stale - st[a].stale || st[b].n - st[a].n : (a === UNASSIGNED ? 1 : b === UNASSIGNED ? -1 : a.localeCompare(b, undefined, {numeric: true, sensitivity: 'base'})));
    const mx = Math.max.apply(null, names.map(n => st[n].n).concat([1]));
    let h = q ? '' : '<div class="pkc all" style="--c:#21bbb0;--i:0"><a href="#/master"><span class="av">★</span><span class="tx"><b>All universities</b><small>Master overview · ' + fmtN(Object.keys(st).length) + ' universities</small></span><span class="ct"><b>' + fmtN(total) + '</b><small>deals</small></span></a></div>';
    h += names.map((n, i) => { const s = st[n]; return '<div class="pkc' + (curU.indexOf(n) >= 0 ? ' on' : '') + '" style="--c:' + hashColor(n) + ';--i:' + (i + 1) + '"><input type="checkbox" class="pkck" data-n="' + esc(n) + '" aria-label="Select ' + esc(n) + '"' + (uSel.has(n) ? ' checked' : '') + '><a href="' + hashFor(n, null, false) + '"><span class="av">' + esc(initials(n)) + '</span><span class="tx"><b title="' + esc(n) + '">' + esc(n) + '</b><small>' + fmtN(s.active) + ' active' + (s.stale ? ' · <span class="od">' + fmtN(s.stale) + ' overdue</span>' : '') + '</small></span><span class="ct"><b>' + fmtN(s.n) + '</b><small>deals</small></span><span class="bar"><i style="width:' + Math.max(3, s.n / mx * 100) + '%"></i></span></a></div>'; }).join('');
    g('pkG').innerHTML = h || '<div class="pkn">No university matches “' + esc(q) + '”.</div>';
    selBar();
  }
  function selBar() {
    const f = g('pkF'); if (!f) return;
    f.hidden = !uSel.size; if (!uSel.size) return;
    const st = uniStats(); let n = 0; uSel.forEach(u => { n += st[u] ? st[u].n : 0; });
    f.innerHTML = '<span class="sp">' + uSel.size + ' selected</span><button type="button" class="ghost" id="pkClr">Clear</button><button type="button" id="pkOpen">Open ' + uSel.size + ' selected · ' + fmtN(n) + ' deals</button>';
    g('pkClr').onclick = () => { uSel.clear(); g('pkG').querySelectorAll('.pkck').forEach(c => { c.checked = false; }); selBar(); };
    g('pkOpen').onclick = () => { const h = hashFor(Array.from(uSel).join(MS), null, false); close(); location.hash = h; };
  }
  function openUnis() {
    mode = 'u'; const st = uniStats();
    shell('Universities', '🎓', fmtN(Object.keys(st).length) + ' universities · ' + fmtN(rows.length) + ' deals. Click one to open it, or tick several to view them together.');
    g('navU').setAttribute('aria-expanded', 'true');
    g('pkQ').oninput = uniDraw; g('pkS').onchange = uniDraw;
    g('pkQ').onkeydown = e => { if (e.key === 'Enter') { const a = g('pkG').querySelector('.pkc:not(.all) a') || g('pkG').querySelector('a'); if (a) { location.hash = a.getAttribute('href'); } } };
    g('pkG').onchange = e => { const c = e.target.closest('.pkck'); if (!c) return; if (c.checked) uSel.add(c.dataset.n); else uSel.delete(c.dataset.n); selBar(); };
    uniDraw(); g('pkQ').focus();
  }
  function repDraw() {
    const q = g('pkQ').value.toLowerCase().trim(), cur = route.type === 'report' ? route.id : '';
    const list = REPORTS.filter(r => !q || (r.title + ' ' + r.desc).toLowerCase().indexOf(q) >= 0);
    let h = q ? '' : '<div class="pkc all" style="--c:#21bbb0;--i:0"><a href="#/reports"><span class="av">▦</span><span class="tx"><b>All reports</b><small>See every report on one page</small></span></a></div>';
    h += list.map((r, i) => '<div class="pkc pkr' + (cur === r.id ? ' on' : '') + '" style="--c:' + hashColor(r.title) + ';--i:' + (i + 1) + '"><a href="#/reports/' + r.id + '"><span class="ri">' + r.icon + '</span><span class="tx"><b>' + esc(r.title) + '</b><small>' + esc(r.desc) + '</small></span><span class="go">Open report →</span></a></div>').join('');
    g('pkG').innerHTML = h || '<div class="pkn">No report matches “' + esc(q) + '”.</div>';
  }
  function openReps() {
    mode = 'r';
    shell('Reports', '📑', 'Pick a report. Each one has filters and clickable numbers that open the students behind them.');
    g('navR').setAttribute('aria-expanded', 'true');
    g('pkQ').oninput = repDraw;
    g('pkQ').onkeydown = e => { if (e.key === 'Enter') { const a = g('pkG').querySelector('.pkr a'); if (a) location.hash = a.getAttribute('href'); } };
    repDraw(); g('pkQ').focus();
  }
  function openPrio() {
    mode = 'p'; const st = prioStats();
    const items = [
      cmSrc && {k: 'stale', f: 'stale', ic: '⏰', c: '#e03131', t: 'Follow-up overdue', d: 'No comment in ' + STALE_DAYS + '+ days', n: st.stale},
      cmSrc && {k: 'nc', f: 'notcontacted', ic: '📵', c: '#f08c00', t: 'Not contacted', d: 'Active, no comment yet', n: st.nc},
      {k: 'pay', f: 'paydue', ic: '💳', c: '#d9a400', t: 'Payment overdue', d: 'Next payment date passed', n: st.pay},
      {k: 'doc', f: 'docpend', ic: '📄', c: '#2a78d6', t: 'Docs pending', d: 'Documents still to collect', n: st.doc}
    ].filter(Boolean);
    box.classList.remove('lst'); g('navP').setAttribute('aria-expanded', 'true');
    box.innerHTML = '<div class="pkh"><h2>🔥 Priority leads</h2><p>' + fmtN(st.any) + ' lead' + (st.any === 1 ? '' : 's') + ' need attention. A lead can be in more than one list. Click a list to open exactly those students.</p><button type="button" class="pkx" aria-label="Close"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button></div>' +
      '<div class="pkg prio" id="pkG">' + items.map((x, i) => '<div class="pkc prc" style="--c:' + x.c + ';--i:' + i + '"><a href="#/master/sheet?f=' + x.f + '"><span class="ri">' + x.ic + '</span><span class="tx"><b>' + esc(x.t) + '</b><small>' + esc(x.d) + '</small></span><span class="ct"><b>' + fmtN(x.n) + '</b><small>' + (x.n === 1 ? 'lead' : 'leads') + '</small></span></a></div>').join('') + '</div>';
    ov.hidden = false;
  }
  g('navP').onclick = e => { e.preventDefault(); if (mode === 'p') close(); else { close(); openPrio(); } };
  g('navU').onclick = e => { e.preventDefault(); if (mode === 'u') close(); else { close(); openUnis(); } };
  g('navR').onclick = e => { e.preventDefault(); if (mode === 'r') close(); else { close(); openReps(); } };
})();
</script>
<script>
/* theme switch: light / dark / follow the device */
(function () {
  var KEY = 'pranav_theme', ORIG = new WeakMap(), mq = window.matchMedia ? matchMedia('(prefers-color-scheme: dark)') : null;
  function saved() { try { var v = localStorage.getItem(KEY); return v === 'light' || v === 'dark' ? v : ''; } catch (e) { return ''; } }
  function isDark() { var m = saved(); return m ? m === 'dark' : !!(mq && mq.matches); }
  function each(fn) {
    for (var i = 0; i < document.styleSheets.length; i++) {
      var rs; try { rs = document.styleSheets[i].cssRules; } catch (e) { continue; }
      for (var j = 0; j < rs.length; j++) if (rs[j].type === 4) fn(rs[j]);
    }
  }
  function apply() {
    var m = saved();
    each(function (r) {
      var t = r.media.mediaText;
      if (!ORIG.has(r)) ORIG.set(r, t);
      var o = ORIG.get(r), dk = /^\(prefers-color-scheme:\s*dark\)$/.test(o), lt = /^not\s*\(prefers-color-scheme:\s*dark\)$/.test(o);
      if (!dk && !lt) return;
      var want = !m ? o : dk ? (m === 'dark' ? 'all' : 'not all') : (m === 'light' ? 'all' : 'not all');
      if (r.media.mediaText !== want) r.media.mediaText = want;
    });
    var d = isDark(), h = document.documentElement;
    h.setAttribute('data-theme', d ? 'dark' : 'light');
    h.style.colorScheme = m || '';
    var b = document.getElementById('btnTheme');
    if (b) { var l = d ? 'Switch to light mode' : 'Switch to dark mode'; b.title = l; b.setAttribute('aria-label', l); }
  }
  window.__applyTheme = apply;
  var btn = document.getElementById('btnTheme');
  if (btn) btn.onclick = function () { try { localStorage.setItem(KEY, isDark() ? 'light' : 'dark'); } catch (e) {} apply(); };
  if (mq && mq.addEventListener) mq.addEventListener('change', apply);
  apply();
  window.addEventListener('load', apply);
})();
</script>
<!--SFX-->
<script>
(function () {
  const KEY = 'pp_sound', VKEY = 'pp_voice_all';
  let on = true, all = false;
  try { on = localStorage.getItem(KEY) !== '0'; all = localStorage.getItem(VKEY) === '1'; } catch (e) {}
  let ctx = null, master = null, speaking = false, pending = null, pendingAt = 0, noise = null;
  const AC = window.AudioContext || window.webkitAudioContext;
  function ac() {
    if (!AC) return null;
    if (!ctx) { try { ctx = new AC(); master = ctx.createGain(); master.gain.value = .55; master.connect(ctx.destination); } catch (e) { ctx = null; return null; } }
    return ctx;
  }
  const locked = () => !!ac() && ctx.state !== 'running';
  function nb() {
    if (noise) return noise;
    const n = ctx.sampleRate * 1.5, b = ctx.createBuffer(1, n, ctx.sampleRate), d = b.getChannelData(0);
    for (let i = 0; i < n; i++) d[i] = Math.random() * 2 - 1; return (noise = b);
  }
  // one oscillator with a pitch glide and a gain envelope
  function tone(f0, f1, dur, type, vol, at, vib) {
    const t = ctx.currentTime + (at || 0), o = ctx.createOscillator(), g = ctx.createGain();
    o.type = type || 'sine'; o.frequency.setValueAtTime(f0, t); if (f1 && f1 !== f0) o.frequency.exponentialRampToValueAtTime(Math.max(20, f1), t + dur);
    g.gain.setValueAtTime(0.0001, t); g.gain.exponentialRampToValueAtTime(vol || .2, t + Math.min(.03, dur / 4)); g.gain.exponentialRampToValueAtTime(0.0001, t + dur);
    if (vib) { const l = ctx.createOscillator(), lg = ctx.createGain(); l.frequency.value = vib[0]; lg.gain.value = vib[1]; l.connect(lg); lg.connect(o.frequency); l.start(t); l.stop(t + dur + .05); }
    o.connect(g); g.connect(master); o.start(t); o.stop(t + dur + .05);
  }
  function hiss(f0, f1, dur, vol, at, q, kind) {
    const t = ctx.currentTime + (at || 0), s = ctx.createBufferSource(), f = ctx.createBiquadFilter(), g = ctx.createGain();
    s.buffer = nb(); f.type = kind || 'bandpass'; f.Q.value = q || 1; f.frequency.setValueAtTime(f0, t); if (f1) f.frequency.exponentialRampToValueAtTime(f1, t + dur);
    g.gain.setValueAtTime(0.0001, t); g.gain.exponentialRampToValueAtTime(vol || .2, t + dur * .25); g.gain.exponentialRampToValueAtTime(0.0001, t + dur);
    s.connect(f); f.connect(g); g.connect(master); s.start(t, Math.random() * .5); s.stop(t + dur + .05);
  }
  const FX = {
    gate() { hiss(160, 90, 1.9, .5, 0, .7, 'lowpass'); tone(95, 55, 1.6, 'sawtooth', .07, .05, [7, 6]); tone(220, 180, .12, 'square', .08, 0); tone(1400, 900, .5, 'triangle', .03, .2, [18, 60]); tone(70, 38, .35, 'sine', .35, 1.75); hiss(900, 200, .25, .25, 1.75, 1, 'lowpass'); },
    whoosh() { hiss(350, 2600, .55, .35, 0, 1.2); },
    chime() { [523.25, 659.25, 783.99, 1046.5].forEach((f, i) => { tone(f, f, .55, 'triangle', .16, i * .11); tone(f * 2, f * 2, .35, 'sine', .04, i * .11); }); },
    eek() { [0, .13, .26].forEach(d => tone(950, 1700, .11, 'sine', .14, d, [30, 40])); },
    fall() { tone(1300, 180, .95, 'sine', .14, 0, [9, 25]); hiss(1500, 300, .9, .06, 0, .8); },
    thud() { tone(130, 38, .2, 'sine', .45, 0); hiss(600, 100, .15, .2, 0, 1, 'lowpass'); },
    cry() { [0, .5, 1.0].forEach(d => { tone(560, 380, .42, 'triangle', .13, d, [8, 14]); tone(1120, 760, .42, 'sine', .03, d); }); },
    grunt() { [0, .28].forEach(d => { tone(125, 82, .26, 'sawtooth', .12, d, [22, 5]); hiss(400, 160, .22, .08, d, 1, 'lowpass'); }); },
    pop() { tone(520, 880, .09, 'sine', .14, 0); },
    blip() { tone(740, 1100, .08, 'sine', .12, 0); },
    unblip() { tone(900, 560, .08, 'sine', .1, 0); },
    ding() { tone(880, 880, .22, 'sine', .15, 0); tone(1320, 1320, .3, 'sine', .12, .12); }
  };
  function play(name) {
    if (!on) return false; const c = ac(); if (!c || !FX[name]) return false;
    if (c.state !== 'running') { try { c.resume(); } catch (e) {} if (c.state !== 'running') return false; }
    try { FX[name](); } catch (e) { return false; } return true;
  }
  /* ---------- voice ---------- */
  const synth = window.speechSynthesis;
  const MALE = /\b(male|ravi|prabhat|madhur|hemant|rishi|david|mark|george|james|guy|daniel|aaron|brian|ryan|christopher|eric|roger|steffan|alex|fred|thomas|oliver|arthur|reed|rocko|ravi)\b/i, FEM = /female|woman|heera|veena|neerja|swara|kalpana|zira|samantha|susan|hazel|aria|jenny|libby|sonia|karen|moira|tessa|fiona|victoria|google हिन्दी/i;
  const isMale = v => !!v && MALE.test(v.name) && !FEM.test(v.name);
  function enVoices() {
    if (!synth) return []; const v = (synth.getVoices() || []).filter(x => /^en/i.test(x.lang));
    const rank = x => (isMale(x) ? 100 : FEM.test(x.name) ? 0 : 40) + (/^en[-_]IN/i.test(x.lang) ? 30 : /^en[-_]GB/i.test(x.lang) ? 20 : /^en[-_]US/i.test(x.lang) ? 15 : 5) + (/natural|neural|online/i.test(x.name) ? 8 : 0) + (/google/i.test(x.name) ? 4 : 0);
    return v.sort((a, b) => rank(b) - rank(a));
  }
  let chosen = ''; try { chosen = localStorage.getItem('pp_voice_name') || ''; } catch (e) {}
  function pickVoice() { const l = enVoices(); return l.find(x => x.name === chosen) || l[0] || null; }
  // emotion -> how the voice is shaped (pitch, speed, loudness)
  const EMO = {
    happy: {p: 1.12, r: 1.1, v: 1}, excited: {p: 1.28, r: 1.22, v: 1}, surprised: {p: 1.4, r: 1.12, v: 1},
    cry: {p: .78, r: .8, v: .8}, angry: {p: .7, r: 1.15, v: 1}, conf: {p: 1.08, r: .9, v: 1}, think: {p: 1, r: .95, v: 1}, idle: {p: 1, r: 1, v: 1}
  };
  function feel(sen, mood) {
    let m = EMO[mood] ? mood : 'idle';
    if (m === 'idle' || m === 'happy' || m === 'think') {
      if (/(!{1,}\s*$)|\b(yay|hooray|great|awesome|congrat|wow|amazing|superb)\b/i.test(sen)) m = 'excited';
      if (/\b(whoa|oh no|oops|really|what)\b|\?!|!\?/i.test(sen)) m = 'surprised';
    }
    const e = EMO[m]; let p = e.p, r = e.r;
    if (/\?\s*$/.test(sen)) p += .1;               // questions lift
    if (/\.\.\.|…/.test(sen)) r -= .08;            // trailing off is slower
    if (/!\s*$/.test(sen) && m !== 'cry' && m !== 'angry') { p += .06; r += .04; }
    return {p: p, r: r, v: e.v, m: m};
  }
  function speakNow(text, mood) {
    if (!synth || !text) return false;
    try {
      synth.cancel(); const v = pickVoice(), male = isMale(v), base = male ? .92 : .78;   // no male voice installed: lower the pitch instead
      const parts = text.match(/[^.!?…]+[.!?…]*/g) || [text]; let left = 0;
      parts.map(x => x.trim()).filter(Boolean).forEach(sen => {
        const f = feel(sen, mood), u = new SpeechSynthesisUtterance(sen);
        if (v) { u.voice = v; u.lang = v.lang; } else u.lang = 'en-IN';
        u.pitch = Math.max(0, Math.min(2, base * f.p)); u.rate = Math.max(.5, Math.min(1.8, f.r)); u.volume = f.v;
        left++;
        u.onstart = () => { speaking = true; window.__ppSpeaking = true; };
        u.onend = u.onerror = () => { if (--left <= 0) { speaking = false; window.__ppSpeaking = false; } };
        setTimeout(() => { try { synth.speak(u); } catch (e) { left--; } }, 0);
      });
      return true;
    } catch (e) { return false; }
  }
  function clean(t) {
    t = String(t || '').replace(/<(style|script)[\s\S]*?<\/\1>/gi, ' ').replace(/<br\s*\/?>|<\/(p|div|li|ul|ol)>/gi, '. ').replace(/<[^>]+>/g, ' ');
    const d = document.createElement('textarea'); d.innerHTML = t; t = d.value;
    t = t.replace(/[\u{1F000}-\u{1FFFF}\u{2190}-\u{21FF}\u{2600}-\u{27BF}\u{FE0F}\u{200D}]/gu, ' ').replace(/₹/g, ' rupees ').replace(/#(\d+)/g, ' number $1').replace(/[“”"*_`]/g, '').replace(/\s*[·•|]\s*/g, ', ').replace(/\s+/g, ' ').replace(/(\.\s*){2,}/g, '. ').trim();
    if (t.length > 320) { t = t.slice(0, 320); const i = Math.max(t.lastIndexOf('. '), t.lastIndexOf('? '), t.lastIndexOf('! ')); t = i > 120 ? t.slice(0, i + 1) : t; }
    return t;
  }
  function speak(text, mood) {
    if (!on) return; const t = clean(text); if (!t) return;
    const wait = mood === 'cry' ? 700 : mood === 'angry' ? 450 : 0;
    if (wait) { play(mood === 'cry' ? 'cry' : 'grunt'); setTimeout(() => speakNow(t, mood), wait); } else speakNow(t, mood);
  }
  function voiceLabel() { const v = pickVoice(); return v ? v.name.replace(/^(Microsoft|Google)\s+/i, '').replace(/\s*-\s*English.*$/i, '').slice(0, 26) + (isMale(v) ? ' (male)' : '') : 'default'; }
  function nextVoice() {
    const l = enVoices(); if (!l.length) return false; const i = l.findIndex(x => x.name === (pickVoice() || {}).name), n = l[(i + 1) % l.length];
    chosen = n.name; try { localStorage.setItem('pp_voice_name', chosen); } catch (e) {} paint(); if (on) speakNow('Hi, I am Pranav. How does this voice sound?', 'happy'); return true;
  }
  function stopVoice() { try { if (synth) synth.cancel(); } catch (e) {} speaking = false; window.__ppSpeaking = false; }
  if (synth && synth.addEventListener) synth.addEventListener('voiceschanged', () => { try { paint(); } catch (e) {} });
  /* ---------- browsers only allow sound after a tap: remember the welcome and play it on the first tap ---------- */
  function unlock() {
    const c = ac(); if (!c) return; try { c.resume(); } catch (e) {}
    if (pending && Date.now() - pendingAt < 15000) { const f = pending; pending = null; setTimeout(f, 60); } else pending = null;
  }
  ['pointerdown', 'keydown', 'touchstart'].forEach(ev => window.addEventListener(ev, unlock, {capture: true, passive: true}));
  window.PPSound = {
    get on() { return on; }, get all() { return all; }, get speaking() { return speaking; }, locked: locked,
    play: play, speak: speak, stop: stopVoice, clean: clean, nextVoice: nextVoice, voices: enVoices,
    later(fn) { pending = fn; pendingAt = Date.now(); },
    toggle() { on = !on; try { localStorage.setItem(KEY, on ? '1' : '0'); } catch (e) {} if (!on) stopVoice(); else play('ding'); paint(); return on; },
    setAll(v) { all = !!v; try { localStorage.setItem(VKEY, all ? '1' : '0'); } catch (e) {} paint(); return all; }
  };
  function paint() {
    const vn = document.getElementById('voiceName'); if (vn) vn.textContent = 'Voice: ' + voiceLabel() + ' · tap to change';
    const m = document.getElementById('soundMode'); if (m) m.textContent = 'Sound & voice: ' + (on ? 'on 🔊' : 'off 🔇');
    const b = document.getElementById('mbSpk'); if (b) { b.classList.toggle('on', all && on); b.title = 'Read replies aloud: ' + (all ? 'always' : 'only when I speak'); b.setAttribute('aria-pressed', all ? 'true' : 'false'); }
  }
  window.__ppSoundPaint = paint;
  document.addEventListener('DOMContentLoaded', paint); window.addEventListener('load', paint); paint();
  window.addEventListener('pagehide', stopVoice);
})();
</script>
<!--/SFX-->
<!--MONKEY-->
<script>
(function () {
  'use strict';
  const g = id => document.getElementById(id);
  const E = s => esc(String(s == null ? '' : s));

  /* ================= text helpers ================= */
  function norm(s) {
    return String(s == null ? '' : s).toLowerCase()
      .replace(/[‘’´`]/g, "'").replace(/'s\b/g, '').replace(/'/g, '')
      .replace(/([a-z])-(?=[a-z])/g, '$1 ')
      .replace(/[^a-z0-9@.+#\/:\-]+/g, ' ').replace(/\s+/g, ' ').trim();
  }
  function toks(s) { return norm(s).split(' ').map(t => t.replace(/^[.:\/\-]+|[.:\/\-]+$/g, '')).filter(Boolean); }
  function dist(a, b, mx) {
    const la = a.length, lb = b.length;
    if (Math.abs(la - lb) > mx) return mx + 1;
    let p2 = null, p1 = new Array(lb + 1), cur;
    for (let j = 0; j <= lb; j++) p1[j] = j;
    for (let i = 1; i <= la; i++) {
      cur = new Array(lb + 1); cur[0] = i; let rm = i;
      for (let j = 1; j <= lb; j++) {
        const c = a.charCodeAt(i - 1) === b.charCodeAt(j - 1) ? 0 : 1;
        let v = Math.min(p1[j] + 1, cur[j - 1] + 1, p1[j - 1] + c);
        if (p2 && i > 1 && j > 1 && a.charCodeAt(i - 1) === b.charCodeAt(j - 2) && a.charCodeAt(i - 2) === b.charCodeAt(j - 1)) v = Math.min(v, p2[j - 2] + 1);
        cur[j] = v; if (v < rm) rm = v;
      }
      if (rm > mx) return mx + 1;
      p2 = p1; p1 = cur;
    }
    return p1[lb];
  }
  const tol = n => n <= 3 ? 0 : n <= 6 ? 1 : 2;
  const near = (a, b) => { if (a === b) return true; const t = tol(Math.max(a.length, b.length)); return t > 0 && dist(a, b, t) <= t; };
  const pad = n => String(n).padStart(2, '0');
  const MONTHS = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'];
  const WDAYS = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
  const NUMW = {one: 1, first: 1, '1st': 1, two: 2, second: 2, '2nd': 2, three: 3, third: 3, '3rd': 3, four: 4, fourth: 4, '4th': 4, five: 5, fifth: 5, '5th': 5, six: 6, sixth: 6, '6th': 6, seven: 7, eight: 8};
  const numOf = t => /^\d+$/.test(t) ? +t : (NUMW[t] || 0);

  /* ================= vocabulary (typo correction) ================= */
  const LEX = {
    status: 'status stage state standing progress stat stages statuses',
    created: 'created creation added opened createdon started joined create',
    when: 'when wen whn whenn',
    who: 'who whom whos hu',
    last: 'last latest recent recently lately previous final',
    worked: 'worked work handled handle handling touched updated modified edited changed commented contacted called spoke followed managed owner owned',
    university: 'university universities uni unis college colleges univ univs universty univercity unversity',
    program: 'program programs programme programmes course courses prog progs',
    batch: 'batch batches intake intakes',
    deal: 'deal deals student students record records candidate candidates people',
    lead: 'lead leads',
    count: 'count counts total totals',
    many: 'many',
    how: 'how hw',
    set: 'set make change update mark edit modify put turn convert move shift fix correct changing updating making marking setting mak mke mrk chnge chage chang updte updat edt',
    paid: 'paid payed payd cleared',
    notpaid: 'unpaid notpaid nonpaid',
    sem: 'sem semester semesters sems semister semestor semestr',
    follow: 'follow followup followups',
    overdue: 'overdue stale delayed late overdues',
    phone: 'phone mobile cell whatsapp contact',
    email: 'email mail emailid',
    name: 'name names',
    team: 'team teams',
    mode: 'mode payment',
    roll: 'roll rollno',
    reference: 'reference references referral',
    docs: 'document documents docs doc',
    active: 'active open ongoing',
    closed: 'closed inactive',
    each: 'each every per wise',
    list: 'list show display give tell',
    help: 'help helps',
    cancel: 'cancel stop abort',
    most: 'most top highest biggest largest maximum max',
    detail: 'detail details info information summary',
    current: 'current present',
    next: 'next upcoming',
    admission: 'admission',
    registration: 'registration',
    payment: 'payment',
    comment: 'comment comments remark remarks note notes',
    today: 'today',
    tomorrow: 'tomorrow tmrw tomorow tommorow tomorrow'
  };
  const LEXV = {}, LEXALL = [];
  Object.keys(LEX).forEach(c => LEX[c].split(' ').forEach(v => { LEXV[v] = c; if (v.length >= 4) LEXALL.push([v, c]); }));
  const STOP = new Set('a an the of for to is was are am in on at by me my i you your please pls plz can could would tell show give get what whats which his her him he she it its this that those these and or with from about has have had do does did kindly let know need want check find see there their them any all also just only into out up'.split(' '));
  const CORE = new Set(['a', 'an', 'the', 'of', 'for', 'to', 'is', 'was', 'in', 'on', 'at', 'by', 'and', 'or', 'with', 'from', 'status', 'stage', 'sem', 'semester', 'paid', 'university', 'program', 'deal', 'student', 'how', 'many', 'what', 'who', 'when', 'which', 'tell', 'show', 'give', 'me', 'my', 'please']);

  let DT = null, DTn = -1;
  function dataTokens() {
    if (DT && DTn === allRows.length) return DT;
    DT = new Set(); DTn = allRows.length;
    allRows.forEach(r => { toks(r._NAME || '').forEach(t => DT.add(t)); });
    cols.forEach(c => { if (c.options) c.options.forEach(o => toks(o.name).forEach(t => DT.add(t))); });
    ['uni', 'program', 'batch', 'mode', 'team', 'lead', 'docstat'].forEach(ro => { const c = colByRole(ro); if (c) allRows.forEach(r => toks(r[c.key] || '').forEach(t => DT.add(t))); });
    return DT;
  }
  function fixTok(t) {
    if (LEXV[t]) return LEXV[t];
    if (t.length < 4 || /\d/.test(t) || dataTokens().has(t)) return t;
    let best = null, bd = 9;
    for (let i = 0; i < LEXALL.length; i++) {
      const v = LEXALL[i][0];
      if (Math.abs(v.length - t.length) > 2) continue;
      const tl = tol(Math.max(v.length, t.length));
      if (tl === 0) continue;
      const d = dist(t, v, tl);
      if (d <= tl && d < bd) { bd = d; best = LEXALL[i][1]; }
    }
    return best || t;
  }

  /* ================= natural dates ================= */
  function nlDate(text) {
    const s = ' ' + norm(text) + ' ';
    const now = new Date(); now.setHours(0, 0, 0, 0);
    let d = null;
    const T = toks(text).map(t => (t.length >= 5 && near(t, 'tomorrow')) ? 'tomorrow' : t);
    const s2 = ' ' + T.join(' ') + ' ';
    let m;
    if ((m = /(\d{4})-(\d{1,2})-(\d{1,2})/.exec(s))) d = new Date(+m[1], +m[2] - 1, +m[3]);
    else if ((m = /\b(\d{1,2})[\/\-.](\d{1,2})[\/\-.](\d{2,4})\b/.exec(s))) d = new Date(+m[3] < 100 ? 2000 + +m[3] : +m[3], +m[2] - 1, +m[1]);
    else if ((m = /\b(\d{1,2})[\/\-](\d{1,2})\b/.exec(s)) && +m[1] >= 1 && +m[1] <= 31 && +m[2] >= 1 && +m[2] <= 12) { d = new Date(now.getFullYear(), +m[2] - 1, +m[1]); if (d < now) d = new Date(now.getFullYear() + 1, +m[2] - 1, +m[1]); }
    else if (/\bday after tomorrow\b/.test(s2) || /\bovermorrow\b/.test(s2)) { d = new Date(now); d.setDate(d.getDate() + 2); }
    else if (/\btomorrow\b/.test(s2)) { d = new Date(now); d.setDate(d.getDate() + 1); }
    else if (/\btoday\b|\btonight\b/.test(s2)) d = new Date(now);
    else if ((m = /\bin (\d+|a|one|two|three|four|five|six|seven|ten) (day|days|week|weeks|month|months)\b/.exec(s2))) {
      const n = /^\d+$/.test(m[1]) ? +m[1] : (m[1] === 'a' ? 1 : (NUMW[m[1]] || (m[1] === 'ten' ? 10 : 1)));
      d = new Date(now);
      if (/week/.test(m[2])) d.setDate(d.getDate() + 7 * n); else if (/month/.test(m[2])) d.setMonth(d.getMonth() + n); else d.setDate(d.getDate() + n);
    } else if (/\bnext week\b/.test(s2)) { d = new Date(now); d.setDate(d.getDate() + 7); }
    else {
      for (let i = 0; i < 7 && !d; i++) {
        const w = WDAYS[i], re = new RegExp('\\b(?:next |this |coming )?(' + w + '|' + w.slice(0, 3) + ')\\b');
        const tk = T.find(t => t.length >= 3 && (t === w.slice(0, 3) || (t.length >= 5 && near(t, w))));
        if (tk) { d = new Date(now); let add = (i - d.getDay() + 7) % 7; if (add === 0 || /\bnext\b/.test(s2)) add = add === 0 ? 7 : add + (/\bnext\b/.test(s2) && add < 7 ? 0 : 0); d.setDate(d.getDate() + add); }
      }
      if (!d) {
        for (let k = 0; k < T.length && !d; k++) {
          const t = T[k];
          let mi = -1;
          for (let q = 0; q < 12; q++) { const mn = MONTHS[q]; if (t.length >= 3 && (mn.indexOf(t) === 0 || (t.length >= 5 && near(t, mn)) || (t === 'sept' && q === 8))) { mi = q; break; } }
          if (mi < 0 || t === 'mar' && false) continue;
          const nb = x => { const mm = /^(\d{1,2})(?:st|nd|rd|th)?$/.exec(x || ''); return mm ? +mm[1] : 0; };
          let day = nb(T[k - 1]) || nb(T[k + 1]);
          let yr = 0; const y = /^(20\d\d)$/.exec(T[k + 2] || '') || /^(20\d\d)$/.exec(T[k + 1] || '') || /^(20\d\d)$/.exec(T[k - 2] || '') || /^(20\d\d)$/.exec(T[k - 1] || '');
          if (y) yr = +y[1];
          if (day >= 1 && day <= 31) {
            let yy = yr || now.getFullYear(); d = new Date(yy, mi, day);
            if (!yr && d < now) d = new Date(yy + 1, mi, day);
          }
        }
      }
      if (!d && (m = /\b(\d{1,2})(?:st|nd|rd|th)?\b/.exec(s2)) && /\b(?:on|by|date)\b/.test(s2) && +m[1] >= 1 && +m[1] <= 31) {
        d = new Date(now.getFullYear(), now.getMonth(), +m[1]); if (d < now) d.setMonth(d.getMonth() + 1);
      }
    }
    if (!d || isNaN(d)) return null;
    let hh = 11, mm = 0, tm;
    if ((tm = /\b(\d{1,2})(?::(\d{2}))?\s*(am|pm)\b/.exec(s))) { hh = +tm[1] % 12 + (tm[3] === 'pm' ? 12 : 0); mm = +(tm[2] || 0); }
    else if ((tm = /\b([01]?\d|2[0-3]):([0-5]\d)\b/.exec(s))) { hh = +tm[1]; mm = +tm[2]; }
    else if (/\bmorning\b/.test(s2)) hh = 10; else if (/\bnoon\b/.test(s2)) hh = 12; else if (/\bafternoon\b/.test(s2)) hh = 15; else if (/\bevening\b/.test(s2)) hh = 18;
    d.setHours(hh, mm, 0, 0);
    return d;
  }
  const dIso = d => d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
  const dIsoT = d => dIso(d) + 'T' + pad(d.getHours()) + ':' + pad(d.getMinutes());
  const dNice = (d, withTime) => d.getDate() + ' ' + MONTHS[d.getMonth()].slice(0, 3).replace(/^./, c => c.toUpperCase()) + ' ' + d.getFullYear() + (withTime ? ', ' + ((d.getHours() % 12) || 12) + ':' + pad(d.getMinutes()) + ' ' + (d.getHours() >= 12 ? 'pm' : 'am') : '');
  function parseStored(s) {
    const m = /(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2}))?/.exec(String(s || ''));
    return m ? new Date(+m[1], +m[2] - 1, +m[3], +(m[4] || 0), +(m[5] || 0)) : null;
  }
  function niceStored(s) { const d = parseStored(s); if (!d) return s ? String(s) : ''; return dNice(d, /\d{2}:\d{2}/.test(String(s))); }
  function agoDays(d) {
    const n = Math.round((Date.now() - d.getTime()) / 864e5);
    return n <= 0 ? 'today' : n === 1 ? 'yesterday' : n < 60 ? n + ' days ago' : n < 730 ? Math.round(n / 30) + ' months ago' : Math.round(n / 365) + ' years ago';
  }

  /* ================= data access ================= */
  const colsEd = () => cols.filter(c => c.editable && !(SCOPE && c.role === 'team'));
  const semCols = () => cols.filter(c => /^semstat\d$/.test(c.role || '')).sort((a, b) => +a.role.slice(7) - +b.role.slice(7));
  const nameOf = r => r._NAME || r.TITLE || ('Deal ' + r.ID);
  const stageOf = r => r.STAGE_ID || '';
  function val(r, role) { const c = colByRole(role); return c ? String(r[c.key] || '') : ''; }
  function lastCm(r) {
    if (!cmSrc) return null;
    const text = String(r[cmSrc] || '').replace(/\r/g, '');
    if (!text.trim()) return null;
    const re = /\s*\|{1,2}\s*(\d{1,2}\/\d{1,2}\/\d{4}\s+\d{1,2}:\d{2}(?::\d{2})?\s*(?:[ap]m)?)\s*(?:\|{1,2}[ \t]*([^\r\n]*))?/gi;
    let last = 0, m, best = null;
    while ((m = re.exec(text))) {
      const c = text.slice(last, m.index).split('\n').filter(l => !/^\s*-{3,}\s*$/.test(l)).join(' ').replace(/\s+/g, ' ').trim();
      last = m.index + m[0].length;
      const ms = tsMs(m[1]);
      if (!best || ms > best.ms) best = {c: c, at: m[1].replace(/\s+/g, ' ').trim(), ms: ms, by: (m[2] || '').trim()};
    }
    return best;
  }
  let ACT = null, ACTat = 0;
  async function activity() {
    if (ACT && Date.now() - ACTat < 45000) return ACT;
    try {
      const r = await fetch('api.php?action=activity', {credentials: 'same-origin'});
      const j = await r.json();
      mergeAV(j); ACT = j && j.ok ? j.items : []; ACTat = Date.now();
    } catch (e) { ACT = ACT || []; }
    return ACT;
  }
  function payState(r, n) {
    const c = cols.find(x => x.role === 'semstat' + n); if (!c) return '';
    const v = String(r[c.key] || '').trim().toLowerCase();
    if (!v || /not\s*paid|unpaid|pending|due/.test(v)) return 'notpaid';
    if (/paid|done|yes/.test(v)) return 'paid';
    return 'other';
  }

  /* ================= entity: which deal? ================= */
  let NI = null, NIn = -1;
  function nameIndex() {
    if (NI && NIn === allRows.length) return NI;
    NIn = allRows.length;
    NI = allRows.map(r => ({r: r, t: toks(nameOf(r)).filter(t => t.length >= 2)})).filter(x => x.t.length);
    return NI;
  }
  function findDeals(T, C) {
    const out = {list: [], how: ''};
    const joined = T.join(' ');
    let m;
    const em = T.find(t => t.indexOf('@') > 0);
    if (em) { const hit = allRows.filter(r => String(r._EMAIL || '').toLowerCase().split(/[,\s;]+/).indexOf(em) >= 0); if (hit.length) { out.list = hit.map(r => ({r: r, s: 9})); out.how = 'email'; return out; } }
    const ph = T.map(t => t.replace(/[^\d]/g, '')).find((d, i) => d.length >= 8 && /^[+\d\-\s]+$/.test(T[i]));
    if (ph) { const key = ph.slice(-10); const hit = allRows.filter(r => String(r._PHONE || '').replace(/\D/g, '').slice(-10) === key); if (hit.length) { out.list = hit.map(r => ({r: r, s: 9})); out.how = 'phone'; return out; } }
    const idm = /(?:\bdeal|\bid|#)\s*(?:no\.?|number)?\s*#?\s*(\d{2,9})\b/.exec(joined) || /#(\d{2,9})\b/.exec(joined);
    let idv = idm ? idm[1] : null;
    if (!idv) { const bare = T.find((t, i) => /^\d{3,9}$/.test(t) && !/^(19|20)\d\d$/.test(t) && !(T[i - 1] && /^(sem|semester|sems)$/.test(C[i - 1] === 'sem' ? 'sem' : T[i - 1]))); if (bare) idv = bare; }
    if (idv) { const r = allRows.find(x => String(x.ID) === idv); if (r) { out.list = [{r: r, s: 9}]; out.how = 'id'; return out; } }
    // by name
    const dt = dataTokens(); const nameTok = new Set(); nameIndex().forEach(x => x.t.forEach(t => nameTok.add(t)));
    const cand = [];
    T.forEach((t, i) => {
      if (/^\d+$/.test(t) || t.length < 2) return;
      if (STOP.has(t)) return;
      const lexed = LEXV[t] || C[i] !== t;
      if (lexed && !(nameTok.has(t) && !CORE.has(t))) return;
      cand.push(t);
    });
    if (!cand.length) return out;
    const res = [];
    nameIndex().forEach(x => {
      let sum = 0, cnt = 0; const usedQ = {};
      x.t.forEach(nt => {
        let best = 0, bj = -1;
        cand.forEach((q, j) => {
          if (usedQ[j]) return;
          let s = 0;
          if (q === nt) s = 1;
          else if (q.length >= 3 && nt.length >= q.length && nt.indexOf(q) === 0 && !/\d$/.test(q)) s = 0.8;
          else if (q.length >= 4 && nt.length >= 4 && near(q, nt)) s = 0.85;
          if (s > best) { best = s; bj = j; }
        });
        if (best >= 0.8) { sum += best; cnt++; usedQ[bj] = 1; }
      });
      if (cnt && sum >= 0.8) res.push({r: x.r, s: sum, c: cnt, n: x.t.length, full: cnt === x.t.length});
    });
    if (!res.length) return out;
    res.sort((a, b) => b.c - a.c || b.s - a.s || (b.full ? 1 : 0) - (a.full ? 1 : 0));
    const top = res[0];
    out.list = res.filter(x => x.c === top.c && x.s >= top.s - 0.15);
    out.how = 'name';
    // exact full-name hit wins
    const full = out.list.filter(x => x.full && x.n === top.c);
    if (full.length === 1 && out.list.length > 1 && full[0].s >= out.list[0].s) out.list = full;
    return out;
  }

  /* ================= entity: filters for counting ================= */
  const GENERIC = new Set(['university', 'universities', 'college', 'institute', 'of', 'the', 'and', 'univ', 'school', 'for']);
  let VD = null, VDn = -1;
  function valueDict() {
    if (VD && VDn === allRows.length) return VD;
    VDn = allRows.length; VD = [];
    ['uni', 'program', 'batch', 'mode', 'team', 'lead', 'docstat', 'stage', 'sem'].forEach(ro => {
      const c = colByRole(ro); if (!c) return;
      const set = new Set();
      allRows.forEach(r => { const v = String(ro === 'uni' ? uniOf(r) : r[c.key] || '').trim(); if (v) set.add(v); });
      if (c.options) c.options.forEach(o => set.add(o.name));
      set.forEach(v => {
        const all = toks(v); let dts = all.filter(t => !GENERIC.has(t));
        if (!dts.length) dts = all;
        if (!dts.length) return;
        VD.push({role: ro, col: c, v: v, t: dts, all: all});
      });
    });
    return VD;
  }
  function STAGEWORDS() { const o = {}; valueDict().forEach(vd => { if (vd.role === 'stage') vd.t.forEach(t => { o[t] = 1; }); }); return o; }
  function matchValues(T, C, skip) {
    const q = T.map((t, i) => ({t: t, i: i})).filter(x => !skip[x.i] && !/^\d+$/.test(x.t) && !STOP.has(x.t) && !(x.t === 'no' && !/^(univers|program|batch|team|mode|stage|status)/.test(T[x.i + 1] || '')) && !(LEXV[x.t] && x.t !== 'active' && !(STAGEWORDS()[x.t] && /^(stage|stages|status)$/.test(T[x.i + 1] || T[x.i - 1] || ''))) || (!skip[x.i] && /^\d+$/.test(x.t)));
    const hits = [];
    const qtoks = T.filter((t, i) => !skip[i]);
    valueDict().forEach(vd => {
      if (vd.role === 'sem' && !/^\d/.test(vd.t.join(''))) { /* 'sem 1' handled below */ }
      const used = [];
      let ok = true;
      for (let k = 0; k < vd.t.length; k++) {
        const nt = vd.t[k]; let f = -1;
        for (let j = 0; j < q.length; j++) {
          if (used.indexOf(j) >= 0) continue;
          const qt = q[j].t;
          if (qt === nt || (qt.length >= 6 && nt.length >= 6 && near(qt, nt)) || (qt.length >= 4 && nt.length > qt.length && nt.indexOf(qt) === 0 && nt.length - qt.length <= 3)) { f = j; break; }
        }
        if (f < 0) { ok = false; break; }
        used.push(f);
      }
      if (ok) hits.push({vd: vd, idx: used.map(j => q[j].i), n: vd.t.length, len: vd.v.length, exact: vd.all.every(x => qtoks.indexOf(x) >= 0)});
    });
    // loose: a single distinctive word that is part of a longer value ("amity" -> Amity University Online)
    valueDict().forEach(vd => {
      q.forEach(x => {
        if (/^\d+$/.test(x.t) || x.t.length < 3 || vd.role === 'stage' || vd.role === 'sem' || vd.role === 'lead' || vd.role === 'docstat' || vd.role === 'mode' || /^(not|online|other|new|general|the|all|none)$/.test(x.t)) return;
        if (!vd.t.some(nt => nt === x.t || (x.t.length >= 6 && nt.length >= 6 && near(x.t, nt)) || (x.t.length >= 4 && nt.length > x.t.length && nt.indexOf(x.t) === 0 && nt.length - x.t.length <= 3))) return;
        if (hits.some(h => h.vd === vd && h.idx.indexOf(x.i) >= 0)) return;
        hits.push({vd: vd, idx: [x.i], n: 1, len: vd.v.length, exact: false});
      });
    });
    // acronyms: "muj" = Manipal University Jaipur, "smu" = Sikkim Manipal University
    valueDict().forEach(vd => {
      if (vd.all.length < 2) return;
      const ac = vd.all.map(x => x[0]).join('');
      if (ac.length < 3) return;
      q.forEach(x => { if (x.t === ac) hits.push({vd: vd, idx: [x.i], n: 1, len: vd.v.length, exact: true}); });
    });
    // "semester" on its own is the payment mode "Semester" (like Lumpsum / Annual), unless it is about a semester number or payment status
    {
      const cs = C.join(' ');
      const modeSem = valueDict().find(vd => vd.role === 'mode' && /^sem(ester)?([ -]?wise)?$/i.test(vd.v.trim()));
      if (modeSem && !/\bsem\w*\s*\d/.test(cs) && !/\b\d(st|nd|rd|th)?\s*sem/.test(cs) && !/\b(paid|payed|notpaid|not paid|unpaid|due|current|fees?|status)\b/.test(cs) && !hits.some(h => h.vd.role === 'mode')) {
        const sk = skip || {};
        const i = T.findIndex((t, k) => /^semesters?$/.test(t) && !sk[k]);
        if (i >= 0) {
          const nxt = T[i + 1] || '', prv = T[i - 1] || '';
          if (!/^(\d+|number|no)$/.test(nxt) && !/^(which|each|per|by|this|last|next|previous|every|all|what|whats)$/.test(prv)) hits.push({vd: modeSem, idx: [i], n: 1, len: modeSem.v.length, exact: true});
        }
      }
    }
    hits.sort((a, b) => (b.exact ? 1 : 0) - (a.exact ? 1 : 0) || b.n - a.n || b.len - a.len);
    const taken = {}, out = [];
    hits.forEach(h => {
      if (h.vd.role === 'sem') return;
      const clash = h.idx.filter(i => taken[i]);
      if (clash.length) {
        const t0 = taken[clash[0]];
        // an ambiguous short word (e.g. "manipal") may belong to several values of the same field: keep them all (OR)
        if (clash.length === h.idx.length && !h.exact && t0.vd.role === h.vd.role && !t0.exact && t0.n === h.n && h.idx.every(i => taken[i] === t0)) out.push(h);
        return;
      }
      h.idx.forEach(i => { taken[i] = h; });
      out.push(h);
    });
    return out;
  }

  /* ================= counting & lists ================= */
  function valueDictHas(t) { return valueDict().some(vd => vd.t.indexOf(t) >= 0 || vd.all.indexOf(t) >= 0); }
  function describeFilter(f) { return f.label; }
  function parseFilters(T, C, skip) {
    const F = [];
    const s = C.join(' ');
    // semester payment
    const semM = /\bsem\w*\s*(\d)\b/.exec(s) || /\b(\d)(?:st|nd|rd|th)?\s*sem/.exec(s);
    const wordNum = (() => { const m = /\bsem\w*\s*(one|two|three|four|five|six)\b/.exec(s) || /\b(first|second|third|fourth|fifth|sixth)\s*sem/.exec(s); return m ? NUMW[m[1]] : 0; })();
    const semN = semM ? +semM[1] : wordNum;
    const wantPaid = /\bpaid\b/.test(s), wantNot = /\bnotpaid\b|\bnot paid\b|\bnot ?payed\b|\bdue\b|\bunpaid\b/.test(s);
    if (semN && (wantPaid || wantNot)) {
      const st = wantNot ? 'notpaid' : 'paid';
      F.push({label: 'Sem ' + semN + ' ' + (st === 'paid' ? 'paid' : 'not paid'), test: r => payState(r, semN) === st});
    } else if (semN) {
      const c = colByRole('sem');
      if (c) F.push({label: 'current semester Sem ' + semN, test: r => new RegExp('(^|\\D)' + semN + '(\\D|$)').test(String(r[c.key] || ''))});
    } else if (wantPaid || wantNot) {
      const st = wantNot ? 'notpaid' : 'paid';
      const c = colByRole('sem');
      F.push({label: (st === 'paid' ? 'paid' : 'not paid') + ' for the current semester', test: r => { const n = semOf(r); return n ? payState(r, n) === st : false; }});
    }
    if (/\boverdue\b/.test(s) || /\bfollow\b.*\b(overdue)\b/.test(s)) F.push({label: 'follow-up overdue', test: r => isStale(r)});
    if (/\bnot contacted\b|\buntouched\b|\bnever contacted\b|\bno comment\b/.test(s) || /\bnot (?:yet )?(?:called|worked|touched|contacted)\b/.test(s)) F.push({label: 'not contacted yet', test: r => isNotContacted(r)});
    if (/\bactive\b/.test(s)) F.push({label: 'active', test: r => !isClosed(r)});
    else if (/\bclosed\b/.test(s)) F.push({label: 'closed', test: r => isClosed(r)});
    if (/\balumni\b/.test(s)) F.push({label: 'alumni', test: r => isAlumni(r)});
    if (/\bre ?reg\w*|\bre registration\b/.test(norm(T.join(' ')))) {
      const want = /\bdone\b|\bcompleted?\b|\bregistered\b/.test(s) ? RR.R : /\bpending\b|\boverdue\b|\blate\b/.test(s) ? RR.O : null;
      if (want) { F.push({label: 're-registration ' + want, test: r => rrStatus(r) === want}); T.forEach((t, i) => { if (/^(pending|done|completed|complete|registered|overdue|late)$/.test(t) || C[i] === 'overdue') skip = Object.assign(skip || {}, {[i]: 1}); }); }
    }
    const hits = matchValues(T, C, skip || {});
    F.consumed = {}; F.roles = {};
    hits.forEach(h => { h.idx.forEach(i => { F.consumed[i] = 1; }); F.roles[h.vd.role] = 1; });
    const byRole = {};
    hits.forEach(h => { const a = (byRole[h.vd.role] = byRole[h.vd.role] || {col: h.vd.col, vals: []}); if (a.vals.indexOf(h.vd.v) < 0) a.vals.push(h.vd.v); });
    Object.keys(byRole).forEach(ro => { const c = byRole[ro].col, vals = byRole[ro].vals, isU = ro === 'uni'; F.push({label: vals.join(' + '), role: ro, test: r => vals.indexOf(String(isU ? uniOf(r) : r[c.key] || '').trim()) >= 0}); });
    return F;
  }
  const NOUN_ROLE = {university: 'uni', program: 'program', batch: 'batch', team: 'team', status: 'stage', mode: 'mode', lead: 'lead', docs: 'docstat'};
  const NOUN_LABEL = {uni: ['university', 'universities'], program: ['program', 'programs'], batch: ['batch', 'batches'], team: ['team', 'teams'], stage: ['stage', 'stages'], mode: ['payment mode', 'payment modes'], lead: ['lead status', 'lead statuses'], docstat: ['document status', 'document statuses']};
  function groupBy(list, role) {
    const c = colByRole(role), m = {};
    list.forEach(r => { const v = role === 'uni' ? uniOf(r) : String(r[c.key] || '').trim() || '(empty)'; m[v] = (m[v] || 0) + 1; });
    return Object.keys(m).map(k => ({k: k, n: m[k]})).sort((a, b) => b.n - a.n || a.k.localeCompare(b.k));
  }
  function filtText(F) { return F.length ? ' (' + F.map(describeFilter).join(' + ') + ')' : ''; }
  function doCount(U) {
    const F = parseFilters(U.T, U.C, U.skip);
    const list = allRows.filter(r => F.every(f => f.test(r)));
    const s = U.s;
    let role = null;
    Object.keys(NOUN_ROLE).forEach(k => { if (!role && new RegExp('\\b' + k + '\\b').test(s) && !(k === 'lead' && /\blead status\b/.test(s) === false && /\bleads?\b/.test(s))) role = NOUN_ROLE[k]; });
    if (role === 'lead' && !/\blead status\b/.test(s)) role = null;
    const roleKey = {uni: 'uni', program: 'program', batch: 'batch', team: 'team', stage: 'stage', mode: 'mode', lead: 'lead', docstat: 'docstat'};
    if (role && F.roles && F.roles[role]) role = null;
    // a word after in/of/for/under that matched nothing: say so instead of counting everything
    for (let i = 0; i < U.T.length - 1; i++) {
      if (/^(in|of|for|under|from|at|named|called)$/.test(U.T[i])) {
        const t = U.T[i + 1];
        if (t && t.length >= 3 && !STOP.has(t) && !LEXV[t] && U.C[i + 1] === t && !/^\d+$/.test(t) && !(F.consumed && F.consumed[i + 1]) && !/^(total|all|the|each|every|not)$/.test(t)) {
          let nearest = null, bd = 9;
          valueDict().forEach(vd => vd.t.forEach(x => { if (x.length >= 4) { const d = dist(t, x, 3); if (d < bd) { bd = d; nearest = vd.v; } } }));
          return {h: 'Hmm, I couldn’t find anything called <b>“' + E(t) + '”</b> in universities, programs, batches or stages. 🙈' + (nearest && bd <= 3 ? '<br>Did you mean <b>' + E(nearest) + '</b>?' : ''), mood: 'conf', chips: nearest && bd <= 3 ? ['How many deals in ' + nearest + '?'] : baseChips()};
        }
      }
    }
    if (role === 'stage' && /\bcount\b|\bhow many deals\b/.test(s) === false && !/\bstages?\b|\bstatus(es)?\b/.test(s)) role = null;
    const each = /\beach\b|\bwise\b/.test(s) || /\bby\b/.test(U.raw);
    const least = /\b(least|fewest|lowest|smallest|minimum)\b/.test(U.raw.toLowerCase());
    const most = /\bmost\b/.test(s) || least;
    if (!allRows.length) return {t: 'I do not see any deals loaded yet. Press “Refresh data” in the account menu once and ask me again.', mood: 'sad'};
    // distinct count of a dimension
    if (role && !each && !most) {
      const grp = groupBy(list.filter(r => role !== 'uni' || uniOf(r) !== UNASSIGNED), role).filter(x => x.k !== '(empty)');
      const nm = NOUN_LABEL[role];
      let h = '<b>' + fmtN(grp.length) + '</b> ' + (grp.length === 1 ? nm[0] : nm[1]) + (F.length ? filtText(F) : '') + ' with deals.';
      if (grp.length) h += list_(grp.slice(0, 12).map(x => E(x.k) + ' <span class="mbn">' + numLink(groupIds(list, role, x.k), x.k + (F.length ? filtText(F) : '')) + '</span>')) + (grp.length > 12 ? '<div class="mbs">…and ' + (grp.length - 12) + ' more.</div>' : '');
      return {h: h, mood: 'happy', ctx: {role: role}};
    }
    if (role && (each || most)) {
      const grp = groupBy(list, role).filter(x => !least || x.k !== '(empty)' && x.k !== UNASSIGNED); if (least) grp.sort((a, b) => a.n - b.n || a.k.localeCompare(b.k));
      const nm = NOUN_LABEL[role];
      const top = most ? grp.slice(0, 1) : grp.slice(0, 15);
      let h = most ? 'The ' + nm[0] + ' with the ' + (least ? 'fewest' : 'most') + ' deals' + filtText(F) + ' is <b>' + E(top[0] ? top[0].k : '—') + '</b> with <b>' + (top[0] ? numLink(groupIds(list, role, top[0].k), top[0].k) : '0') + '</b> deals.' : 'Deals by ' + nm[0] + filtText(F) + ':';
      if (!most) h += list_(top.map(x => E(x.k) + ' <span class="mbn">' + numLink(groupIds(list, role, x.k), x.k + (F.length ? filtText(F) : '')) + '</span>')) + (grp.length > 15 ? '<div class="mbs">…and ' + (grp.length - 15) + ' more.</div>' : '');
      else if (grp.length > 1) h += list_(grp.slice(1, 5).map(x => E(x.k) + ' <span class="mbn">' + numLink(groupIds(list, role, x.k), x.k) + '</span>')) + '<div class="mbs">Runners up above.</div>';
      return {h: h, mood: 'happy'};
    }
    const n = list.length;
    let h;
    const left = U.T.filter((t, i) => !(F.consumed && F.consumed[i]) && !(U.skip && U.skip[i]) && !STOP.has(t) && !LEXV[t] && U.C[i] === t && t.length >= 3 && !/^\d+$/.test(t) && !valueDictHas(t) && !/^(overdue|stale|active|closed|alumni|paid|unpaid|due|contacted|touched|worked|called|untouched|created|added|joined|today|yesterday|tomorrow|week|month|year|monday|tuesday|wednesday|thursday|friday|saturday|sunday|number|numbers|total|count|many|much|show|list)$/.test(t));
    const warn = left.length && F.length ? '<div class="mbs">⚠️ I didn’t understand “' + E(left.slice(0, 3).join(' ')) + '”, so it is <b>not</b> in this count.</div>' : '';
    if (!F.length) h = 'There are <b>' + numLink(list, 'All deals') + '</b> deals (students) in total' + (SCOPE ? ' for your team' : '') + '.';
    else h = '<b>' + numLink(list, F.map(describeFilter).join(' + ')) + '</b> deal' + (n === 1 ? '' : 's') + filtText(F) + '.';
    const ex = F.length && n && n <= 5 ? list_(list.map(r => '<a href="#" data-deal="' + E(r.ID) + '">' + E(nameOf(r)) + '</a> <span class="mbs">#' + E(r.ID) + '</span>')) : '';
    return {h: h + warn + ex, mood: n ? 'happy' : 'conf', ctx: {}};
  }
  /* clickable numbers: open exactly those deals in the table */
  let SELN = 0;
  function mkSel(list, label) {
    const M = (window.__botSel = window.__botSel || {});
    const key = 'b' + (++SELN) + Date.now().toString(36).slice(-4);
    M[key] = {label: label || 'From Pranav', ids: list.map(r => String(r.ID))};
    const ks = Object.keys(M); if (ks.length > 15) ks.slice(0, ks.length - 15).forEach(k => { delete M[k]; });
    try { sessionStorage.setItem('pranav_botsel', JSON.stringify(M)); } catch (e) {}
    return key;
  }
  function numLink(list, label) {
    const n = list.length;
    if (!n) return fmtN(0);
    return '<a href="#" class="mbnum" data-sel="' + mkSel(list, label) + '" title="Open these ' + n + ' deal' + (n === 1 ? '' : 's') + ' in the table">' + fmtN(n) + '</a>';
  }
  function groupIds(list, role, k) { const c = colByRole(role); return list.filter(r => (role === 'uni' ? uniOf(r) : (c ? String(r[c.key] || '').trim() : '') || '(empty)') === k); }
  const list_ = a => '<ul class="mbl2">' + a.map(x => '<li>' + x + '</li>').join('') + '</ul>';

  /* ================= deal answers ================= */
  function semLine(r) {
    const sc = semCols(); if (!sc.length) return '';
    const parts = sc.map(c => { const n = c.role.slice(7), v = String(r[c.key] || '').trim() || 'Not Paid'; return 'Sem ' + n + ': ' + v; });
    return parts.join(' · ');
  }
  function rrText(r) { try { return rrStatus(r); } catch (e) { return ''; } }
  function dealCard(r) {
    const cm = lastCm(r);
    const rows_ = [
      ['Student', nameOf(r) + ' · #' + r.ID],
      ['Stage', stageOf(r) || '—'],
      ['University', uniOf(r)],
      ['Program', val(r, 'program') || '—'],
      ['Batch', val(r, 'batch') || '—'],
      ['Current sem', val(r, 'sem') || '—'],
      ['Semester fees', semLine(r) || '—'],
      ['Lead status', val(r, 'lead') || '—'],
      ['Documents', val(r, 'docstat') || '—'],
      ['Next follow-up', niceStored(val(r, 'fuview') || val(r, 'fuupd')) || '—'],
      ['Created', r.DATE_CREATE ? niceStored(r.DATE_CREATE) + ' (' + agoDays(parseStored(r.DATE_CREATE) || new Date()) + ')' : '—']
    ];
    let h = '<div class="mbcard">' + rows_.map(x => '<div><span>' + E(x[0]) + '</span><b>' + E(x[1]) + '</b></div>').join('');
    if (cm) h += '<div class="wide"><span>Last comment</span><b>' + E(cm.c.length > 160 ? cm.c.slice(0, 157) + '…' : cm.c) + '</b><i>' + E([cm.by, cm.at].filter(Boolean).join(' · ')) + '</i></div>';
    return h + '</div>';
  }
  const ATTRS = [
    ['created', /\bcreated\b|\bwhen\b.*\b(deal|added|opened|started|joined)\b|\bwhen\b.*\bcreated\b/],
    ['worked', /\bwho\b.*\b(worked|owner|touched|commented|contacted)\b|\b(last|latest) (worked|contacted|commented|touched)\b|\bwho\b.*\bhandling\b|\bworked (on|last)\b/],
    ['followup', /\bfollow\b|\bnext (call|date)\b/],
    ['phone', /\bphone\b|\bmobile\b|\bcell\b|\bwhatsapp\b|\bcontact\b(?! person)/],
    ['email', /\bemail\b/],
    ['uni', /\buniversity\b/],
    ['program', /\bprogram\b/],
    ['batch', /\bbatch\b/],
    ['mode', /\bmode\b|\bpayment mode\b/],
    ['sempay', /\bsem\w*\s*\d\b.*\b(status|paid|notpaid)\b|\b(status|paid|notpaid)\b.*\bsem\w*\s*\d\b|\bfee\b|\bfees\b/],
    ['curs', /\bcurrent\b.*\bsem|\bwhich sem/],
    ['lead', /\blead status\b|\blead\b.*\bstatus\b|\bhot\b|\bwarm\b|\bcold\b/],
    ['docs', /\bdocs\b.*\bstatus\b|\bdocs\b/],
    ['comment', /\bcomment\b|\bremark\b|\bnote\b/],
    ['stage', /\bstatus\b|\bstage\b|\bwhere\b|\bprogress\b/],
    ['detail', /\bdetail\b|\binfo\b|\bsummary\b|\bwho is\b|\babout\b/]
  ];
  function attrsOf(s) { return ATTRS.filter(a => a[1].test(s)).map(a => a[0]); }
  async function answerAttr(r, attrs, s) {
    const nm = '<b>' + E(nameOf(r)) + '</b>';
    const parts = [];
    let mood = 'happy';
    for (const a of attrs) {
      if (a === 'created') {
        const d = parseStored(r.DATE_CREATE);
        parts.push(d ? nm + '’s deal #' + E(r.ID) + ' was created on <b>' + E(niceStored(r.DATE_CREATE)) + '</b> (' + agoDays(d) + ').' : 'I cannot see a created date for this deal.');
      } else if (a === 'worked') {
        const cm = lastCm(r), act = (await activity()).filter(e => String(e.deal) === String(r.ID) && e.type !== 'login' && e.changes);
        const ea = act[0];
        let h = '';
        if (cm) h += 'The last comment on ' + nm + '’s deal is from <b>' + E(cm.by || 'someone who did not sign it') + '</b> on <b>' + E(cm.at) + '</b>: “' + E(cm.c.length > 140 ? cm.c.slice(0, 137) + '…' : cm.c) + '”';
        if (ea) h += (h ? '<br>' : '') + 'Last edit made from this dashboard: <b>' + E(ea.user) + '</b> on <b>' + E(niceStored(String(ea.at).replace('T', ' ').slice(0, 16))) + '</b>.';
        if (!h) { h = 'No one has commented on this deal yet, and I see no dashboard edits for it. It looks untouched. 🙈'; mood = 'conf'; }
        parts.push(h);
      } else if (a === 'followup') {
        const v = val(r, 'fuview') || val(r, 'fuupd');
        parts.push(v ? 'Next follow-up for ' + nm + ' is <b>' + E(niceStored(v)) + '</b>.' : nm + ' has no follow-up date set.');
      } else if (a === 'phone') parts.push(nm + '’s phone: <b>' + E(r._PHONE || 'not on file') + '</b>');
      else if (a === 'email') parts.push(nm + '’s email: <b>' + E(r._EMAIL || 'not on file') + '</b>');
      else if (a === 'uni') parts.push(nm + ' is in <b>' + E(uniOf(r)) + '</b>.');
      else if (a === 'program') parts.push(nm + '’s program is <b>' + E(val(r, 'program') || 'not set') + '</b>.');
      else if (a === 'batch') parts.push(nm + ' is in batch <b>' + E(val(r, 'batch') || 'not set') + '</b>.');
      else if (a === 'mode') parts.push(nm + ' pays by <b>' + E(val(r, 'mode') || 'not set') + '</b>.');
      else if (a === 'curs') parts.push(nm + ' is in <b>' + E(val(r, 'sem') || 'no semester set') + '</b>.');
      else if (a === 'lead') parts.push(nm + '’s lead status is <b>' + E(val(r, 'lead') || 'not set') + '</b>.');
      else if (a === 'docs') parts.push(nm + '’s documents status: <b>' + E(val(r, 'docstat') || 'not set') + '</b>.');
      else if (a === 'comment') { const cm = lastCm(r); parts.push(cm ? 'Latest comment on ' + nm + ': “' + E(cm.c) + '” — <i>' + E([cm.by, cm.at].filter(Boolean).join(', ')) + '</i>' : 'No comments on ' + nm + '’s deal yet.'); }
      else if (a === 'sempay') {
        const m = /\bsem\w*\s*(\d)\b/.exec(s) || /\b(\d)(?:st|nd|rd|th)?\s*sem/.exec(s);
        if (m) { const c = cols.find(x => x.role === 'semstat' + m[1]); parts.push(c ? nm + '’s Semester ' + m[1] + ' status: <b>' + E(String(r[c.key] || '').trim() || 'Not Paid') + '</b>.' : 'There is no Semester ' + m[1] + ' status field.'); }
        else parts.push(nm + '’s semester fees: ' + E(semLine(r)));
      } else if (a === 'stage') parts.push(nm + ' is at stage <b>' + E(stageOf(r) || 'not set') + '</b>.');
    }
    const showCard = !attrs.length || attrs.indexOf('detail') >= 0 || (attrs.length === 1 && attrs[0] === 'stage');
    return {h: parts.filter(Boolean).join('<br>') + (showCard ? dealCard(r) : ''), mood: mood, deal: r, actions: true};
  }

  /* ================= editing ================= */
  const FIELD_RULES = [
    ['fuupd', /\bfollow\b/],
    ['lastpay', /\blast\b.*\bpayment\b|\blast pay\b/],
    ['nextpay', /\bnext\b.*\bpayment\b|\bre ?registration date\b|\bnext pay\b/],
    ['lead', /\blead status\b|\blead stage\b|\blead\b(?!s)/],
    ['docstat', /\bdocs\b|\bdocuments?\b/],
    ['refadm', /\breference admission\b|\bref admission\b/],
    ['refhas', /\breference\b|\bhas reference\b/],
    ['mode', /\bmode\b|\bpayment\b/],
    ['roll', /\broll\b/],
    ['reg', /\bregistration date\b|\breg date\b|\bregistration\b/],
    ['adm', /\badmission date\b|\badmission\b/],
    ['team', /\bteam\b/],
    ['batch', /\bbatch\b/],
    ['program', /\bprogram\b/],
    ['uni', /\buniversity\b/],
    ['_PHONE', /\bphone\b|\bmobile\b|\bcell\b|\bwhatsapp\b|\bnumber\b/],
    ['_EMAIL', /\bemail\b/],
    ['_NAME', /\bname\b/]
  ];
  function fieldsIn(clause, wholeStr) {
    const out = [];
    const semM = /\bsem\w*\s*(\d)\b/.exec(clause) || /\b(\d)(?:st|nd|rd|th)?\s*sem/.exec(clause) || (() => { const m = /\bsem\w*\s*(one|two|three|four|five|six)\b/.exec(clause) || /\b(first|second|third|fourth|fifth|sixth)\s*sem/.exec(clause); return m ? [m[0], NUMW[m[1]]] : null; })();
    const cur = /\bcurrent\b/.test(clause);
    if (semM && !cur) { const c = cols.find(x => x.role === 'semstat' + semM[1]); if (c) out.push(c); else out.push({missingSem: +semM[1]}); return out; }
    if (cur && /\bsem\b/.test(clause)) { const c = colByRole('sem'); if (c) out.push(c); return out; }
    for (const fr of FIELD_RULES) { if (fr[1].test(clause)) { const c = colByRole(fr[0]); if (c) { out.push(c); break; } } }
    if (!out.length && /\bstatus\b/.test(clause) && !/\bsem\b/.test(clause)) { const c = colByRole('stage'); if (c) out.push(c); }
    if (!out.length && /\bsem\b/.test(clause)) { const c = colByRole('sem'); if (c) out.push(c); }
    return out;
  }
  const PAY_WORDS = /\bpaid\b|\bnotpaid\b|\bnot paid\b|\bnot ?payed\b|\bdue\b|\bunpaid\b|\bpending\b/;
  function matchOption(col, clause, rawToks) {
    const opts = col.options || [];
    const isPay = /^semstat/.test(col.role || '');
    if (isPay) {
      const wantNot = /\bnotpaid\b|\bnot paid\b|\bnot ?payed\b|\bunpaid\b|\bdue\b|\bpending\b|\bnot\b/.test(clause), wantPaid = /\bpaid\b/.test(clause);
      if (wantNot) { const o = opts.find(x => /not\s*paid|unpaid|pending|due/i.test(x.name)); if (o) return o; }
      if (wantPaid) { const o = opts.find(x => /^paid/i.test(x.name)) || opts.find(x => /paid/i.test(x.name) && !/not/i.test(x.name)); if (o) return o; }
    }
    const q = rawToks.filter(t => !STOP.has(t));
    let best = null, bs = 0;
    opts.forEach(o => {
      const ot = toks(o.name).filter(t => !STOP.has(t));
      if (!ot.length) return;
      let hit = 0;
      ot.forEach(t => { if (q.some(qt => qt === t || (qt.length >= 5 && t.length >= 5 && near(qt, t) && !LEXV[qt]) || (qt.length >= 4 && t.indexOf(qt) === 0 && !LEXV[qt]))) hit++; });
      const sc = hit / ot.length + (hit === ot.length ? ot.length * 0.01 : 0);
      if (hit && sc > bs) { bs = sc; best = o; }
    });
    if (!best) return null;
    const ties = opts.filter(o => { const ot = toks(o.name).filter(t => !STOP.has(t)); let hit = 0; ot.forEach(t => { if (q.some(qt => qt === t || (qt.length >= 5 && t.length >= 5 && near(qt, t) && !LEXV[qt]) || (qt.length >= 4 && t.indexOf(qt) === 0 && !LEXV[qt]))) hit++; }); return ot.length && (hit / ot.length + (hit === ot.length ? ot.length * 0.01 : 0)) >= bs - 1e-9; });
    if (ties.length > 1) return null;
    return bs >= 0.99 ? best : (bs >= 0.5 && ot_len(best) > 1 ? best : null);
  }
  const ot_len = o => o ? toks(o.name).length : 0;
  function parseEdit(U, deal) {
    const s = U.s;
    const clauses = s.split(/\s*(?:,|;|&|\band\b|\balso\b|\bplus\b|\bthen\b)\s*/).filter(x => x.trim());
    const rawClauses = U.raw.split(/\s*(?:,|;|&|\band\b|\balso\b|\bplus\b|\bthen\b)\s*/i);
    const items = []; let pendFields = [], unresolved = [];
    clauses.forEach((cl, ci) => {
      const raw = rawClauses[ci] || cl;
      const rt = toks(raw);
      const sepM = /\b(to|as|is)\b|=/.exec(cl);
      const pre = sepM ? cl.slice(0, sepM.index) : cl;
      let post = sepM ? cl.slice(sepM.index + sepM[0].length) : cl;
      post = post.replace(/\s+\b(for|of)\b\s.*$/, '');
      const clV = post.trim() ? post : cl;
      const rsep = /\b(to|as|is)\b|=/i.exec(raw);
      let rpost = rsep ? raw.slice(rsep.index + rsep[0].length) : raw;
      rpost = rpost.replace(/\s+\b(for|of)\b\s.*$/i, '');
      const rtV = toks(rpost.trim() ? rpost : raw);
      let fields = fieldsIn(pre, s);
      if (!fields.length && sepM) { fields = fieldsIn(cl, s); if (fields.length && /\bstatus\b/.test(post) && !/\bsem\b/.test(cl)) { const stc = colByRole('stage'); if (stc) fields = [stc]; } if (fields.length && fields[0].role === 'docstat' && /\bdocs\b/.test(post) && !/\bdocs\b/.test(pre)) { const stc = colByRole('stage'); if (stc) fields = [stc]; } }
      if (!fields.length && pendFields.length === 0 && ci > 0 && /^\W*\d\b/.test(cl) && items.length && items[items.length - 1].col && /^semstat/.test(items[items.length - 1].col.role || '')) {
        const n = (/^\W*(\d)/.exec(cl) || [])[1]; const c = cols.find(x => x.role === 'semstat' + n); if (c) fields = [c];
      }
      const missing = fields.filter(f => f.missingSem);
      missing.forEach(m => unresolved.push('There is no Semester ' + m.missingSem + ' status field on this dashboard.'));
      fields = fields.filter(f => !f.missingSem);
      let value = null, vcol = null;
      const tryVal = col => {
        const kind = fieldKind(col);
        if (col.key === '_PHONE') { const m = /\+?\d[\d\s\-]{6,}\d/.exec(raw); return m ? {disp: m[0].replace(/[\s]+/g, ''), send: m[0].replace(/[\s]+/g, '')} : null; }
        if (col.key === '_EMAIL') { const m = /[\w.+\-]+@[\w\-]+\.[\w.\-]+/.exec(raw); return m ? {disp: m[0], send: m[0]} : null; }
        if (col.key === '_NAME') { const m = /(?:\bto\b|\bas\b|=|\bis\b)\s+(.+?)(?:\s+\bfor\b.*)?$/i.exec(raw); return m && m[1].trim().length > 1 ? {disp: m[1].trim(), send: m[1].trim()} : null; }
        if (kind === 'select') { const o = matchOption(col, clV, rtV); return o ? {disp: o.name, send: o.id} : null; }
        if (kind === 'bool') { if (/\b(yes|y|true|haan|ha)\b/.test(clV)) return {disp: 'Yes', send: 'Yes'}; if (/\b(no|false|nahi|nope)\b/.test(clV)) return {disp: 'No', send: 'No'}; return null; }
        if (kind === 'date' || kind === 'datetime') { const d = nlDate(raw); return d ? (kind === 'date' ? {disp: dNice(d, false), send: dIso(d)} : {disp: dNice(d, true), send: dIsoT(d)}) : null; }
        const m = /(?:\bto\b|\bas\b|=|\bis\b)\s+(.+?)(?:\s+\bfor\b.*)?$/i.exec(raw);
        return m && m[1].trim() ? {disp: m[1].trim(), send: m[1].trim()} : null;
      };
      if (fields.length) {
        const gotV = [];
        fields.forEach(f => { const v = tryVal(f); if (v) { items.push({col: f, v: v}); gotV.push(f); } else pendFields.push(f); });
        if (gotV.length && pendFields.length) { const still = []; pendFields.forEach(f => { if (fields.includes(f)) { still.push(f); return; } const v = tryVal(f); if (v) items.push({col: f, v: v}); else still.push(f); }); pendFields = still; }
        if (pendFields.length && !fields.some(f => !pendFields.includes(f))) { /* all pending */ }
      } else if (!missing.length) {
        // value with no field: give it to pending fields, or infer a column
        let done = false;
        if (pendFields.length) {
          const still = [];
          pendFields.forEach(f => { const v = tryVal(f); if (v) { items.push({col: f, v: v}); done = true; } else still.push(f); });
          pendFields = still;
        }
        if (!done && (PAY_WORDS.test(cl) || U.hasSetVerb)) {
          if (PAY_WORDS.test(cl)) { const sc = semCols(); const m = /\bsem\w*\s*(\d)\b/.exec(s); if (m) { const c = sc.find(x => x.role === 'semstat' + m[1]); if (c) { const v = tryVal(c); if (v) { items.push({col: c, v: v}); done = true; } } } if (!done) unresolved.push({askSem: true, text: cl}); }
          else {
            const cand = [];
            colsEd().forEach(c => { if (fieldKind(c) === 'select' && !/^semstat/.test(c.role || '')) { const o = matchOption(c, cl, rt); if (o) cand.push({col: c, v: {disp: o.name, send: o.id}}); } });
            if (cand.length === 1) items.push(cand[0]);
            else if (cand.length > 1) unresolved.push({ambig: cand.map(x => x.col.label), text: cl});
          }
        }
      }
    });
    pendFields.forEach(f => unresolved.push({noValue: f}));
    return {items: items, unresolved: unresolved};
  }

  /* ================= understand ================= */
  function understand(raw) {
    const T = toks(raw), C = T.map(fixTok);
    const s = C.join(' ');
    const U = {raw: raw, T: T, C: C, s: s, skip: {}};
    U.hasSetVerb = /\bset\b/.test(s);
    U.question = /\?\s*$/.test(raw) || /^(what|whats|when|who|how|which|is|are|does|did|do|can you tell|tell me|show)\b/.test(s);
    return U;
  }
  const DOMAIN_RX = /\b(status|university|program|batch|deal|lead|count|sem|follow|overdue|phone|email|team|mode|roll|reference|docs|active|closed|comment|created|worked|paid|notpaid|detail|payment|admission|registration|student|students|stage|fee|fees|dashboard|report|reports|login|password|refresh|filter|profile|bitrix|crm|pipeline|alumni|rereg)\b/;
  const SOCIAL = [
    [/^(hi+|hello+|hey+|hola|namaste|yo|hii+|helo|hallo|good (morning|afternoon|evening|night)|gm)\b/, 'hi'],
    [/^(thanks|thank you|thankyou|thx|tysm|ty|thanku|thnx|cool thanks|great thanks)\b/, 'thanks'],
    [/^(bye|goodbye|see you|cya|good night|gn|tc)\b/, 'bye'],
    [/\b(who are you|what are you|your name|who r u|are you a bot|ur name)\b/, 'who'],
    [/\b(what can you do|help|how to use|what do you do|what all|abilities|commands|menu|options)\b/, 'help'],
    [/^(ok|okay|k|fine|nice|great|good|awesome|cool|wow|hmm+|alright|sure)\W*$/, 'ok'],
    [/\b(how are you|how r u|hows it going|how do you do)\b/, 'howru'],
    [/^(lol|haha+|hehe+|lmao)\W*$/, 'lol']
  ];

  /* ================= replies ================= */
  const TRAINED = [
    'Oo-oo-ah-ah… 😭 the admin has <b>not trained me</b> on that yet. I’m sitting here sobbing into my banana. Please tell him (nicely) to teach me! 🍌',
    'Waaah! 😢 I don’t know that one — the admin never taught me. I tried reading the manual but I ate the pages. Please tell the admin, pretty please! 🙏🍌',
    '😭 *sniff* That’s above my banana grade! The admin hasn’t trained me for this. Go tell him, and tell him I’m crying!',
    'Oh, I wish I knew! 🥺 But the admin hasn’t trained me on that. Please tell him I need more lessons — I will even do my homework (and eat it).'
  ];
  const OOT = [
    '🙈 Whoa, that’s way outside my jungle! I only talk about <b>this website</b> — deals, students, universities, programs, payments and follow-ups. Ask me something from here and I’ll swing right in! 🌴',
    'Going out of the topic, my friend! I’m only trained for this dashboard. Try asking about a student, a university or a count of deals. 🍌',
    '🌴 That’s not in my jungle! I know only this website’s data. Ask me about a student’s status, how many universities, or to open a deal to edit.'
  ];
  let ti = 0, oi = 0;
  const notTrained = () => ({h: TRAINED[ti++ % TRAINED.length], mood: 'cry', copy: true});
  const outOfTopic = () => ({h: OOT[oi++ % OOT.length], mood: 'conf'});
  const HELP = '<b>I’m Pranav</b> — I read this dashboard’s data and I find things fast. Editing is your job, so I open the lead for you. Typos are fine!<ul class="mbl2"><li><b>A student:</b> “status of Rahul”, “phone of Rahul”, “who worked last on deal 108”, “when was Rahul’s deal created”</li><li><b>Counts:</b> “how many deals were created this month”, “how many in muj + smu + amity”, “overdue deals”, “sem 1 paid in Amity”, “which university has the most deals”</li><li><b>Latest:</b> “latest deal created”, “last 5 deals”</li><li><b>My work:</b> “last deal I worked on”, “last university I worked on”, “what filter was I using”</li><li><b>Filters:</b> “filter muj + smu + amity”, “only mba and bba”, “clear filters”</li><li><b>Reports:</b> “open alumni report”, “create a report of university by stage for muj + smu”</li><li><b>Edit:</b> “edit Rahul”, “change Rahul’s sem 1 status”. I open that lead’s edit form and walk you through it. No questions.</li><li><b>How-to:</b> refresh data, change password, export CSV, what “overdue” means…</li></ul>🍌';

  /* ================= UI ================= */
  const FACE = (
    '<g class="mbbody"><circle cx="36" cy="106" r="22" fill="#8a5632"/><circle cx="36" cy="106" r="12" fill="#f0c7a0"/><circle cx="164" cy="106" r="22" fill="#8a5632"/><circle cx="164" cy="106" r="12" fill="#f0c7a0"/>' +
    '<circle cx="100" cy="102" r="64" fill="#9a6338"/><path d="M100 72c-14-14-46-6-46 24 0 14 6 22 14 28 4 18 18 30 32 30s28-12 32-30c8-6 14-14 14-28 0-30-32-38-46-24z" fill="#f4d5b0"/>' +
    '<g class="mbe"><ellipse cx="78" cy="102" rx="13" ry="15" fill="#fff"/><g class="mbp"><circle cx="78" cy="104" r="7" fill="#2a1a12"/><circle cx="80.5" cy="101" r="2.3" fill="#fff"/></g></g>' +
    '<g class="mbe"><ellipse cx="122" cy="102" rx="13" ry="15" fill="#fff"/><g class="mbp"><circle cx="122" cy="104" r="7" fill="#2a1a12"/><circle cx="124.5" cy="101" r="2.3" fill="#fff"/></g></g>' +
    '<path class="mbhap" d="M66 106q12-16 24 0M110 106q12-16 24 0"/>' +
    '<path class="mbbr mbbl" d="M64 82q14-9 28-1"/><path class="mbbr mbbrr" d="M108 81q14-8 28 1"/>' +
    '<ellipse cx="100" cy="127" rx="9" ry="6" fill="#6b3f22"/>' +
    '<path class="mbm mbm1" d="M84 141q16 14 32 0"/><path class="mbm mbm2" d="M84 148q16-16 32 0"/><path class="mbm mbm3" d="M84 144q8-7 16 0t16 0"/><path class="mbm4" d="M80 136q20 30 40 0z" fill="#4a1f12" stroke="#4a1f12" stroke-width="3" stroke-linejoin="round"/><path class="mbm5" d="M92 150q8 8 16 0q-8-6-16 0z" fill="#ef6f85"/>' +
    '<path class="mbt mbt1" d="M70 118q-6 12 0 18q6-6 0-18z"/><path class="mbt mbt2" d="M130 118q-6 12 0 18q6-6 0-18z"/></g>' +
    '<text class="mbq" x="150" y="48" font-size="46" font-weight="800" fill="#2cdace">?</text>'
  );
  const css = '' +
    '.mbl{position:fixed;right:22px;bottom:22px;z-index:40;width:68px;height:68px;border-radius:50%;border:0;padding:0;cursor:pointer;background:linear-gradient(135deg,#46e8dd,#288a84);box-shadow:0 12px 28px rgba(40,138,132,.45);display:grid;place-items:center;transition:transform .2s,box-shadow .2s;animation:mbPop .6s .8s both}' +
    '.mbl:hover{transform:translateY(-3px) scale(1.06);box-shadow:0 18px 34px rgba(40,138,132,.55)}.mbl svg{width:54px;height:54px;overflow:visible}' +
    '.mbl .mbtip{position:absolute;right:76px;top:50%;transform:translateY(-50%);white-space:nowrap;background:var(--card);color:var(--text);border:1px solid var(--line);padding:7px 12px;border-radius:12px;font-weight:700;font-size:13px;box-shadow:0 8px 20px rgba(19,51,49,.2);opacity:0;pointer-events:none;transition:opacity .2s}' +
    '.mbl:hover .mbtip,.mbl.hint .mbtip{opacity:1}' +
    '@keyframes mbPop{from{transform:scale(0) rotate(-30deg)}to{transform:none}}' +
    '.mbp2{position:fixed;right:22px;bottom:104px;z-index:41;width:396px;max-width:calc(100vw - 24px);height:min(620px,calc(100vh - 128px));display:flex;flex-direction:column;background:var(--card);color:var(--text);border:1px solid var(--line);border-radius:22px;box-shadow:0 30px 70px rgba(19,51,49,.38);overflow:hidden;animation:mbIn .28s cubic-bezier(.2,.9,.3,1.1) both;transform-origin:100% 100%}' +
    '.mbp2[hidden]{display:none}@keyframes mbIn{from{opacity:0;transform:translateY(18px) scale(.92)}to{opacity:1;transform:none}}' +
    '.mbh{display:flex;align-items:center;gap:12px;padding:14px 16px;background:linear-gradient(120deg,#2cdace,#e18946);color:#fff}' +
    '.mbh .av{width:50px;height:50px;border-radius:50%;background:rgba(255,255,255,.2);display:grid;place-items:center;flex:none}.mbh .av svg{width:46px;height:46px;overflow:visible}' +
    '.mbh h3{margin:0;font-size:17px;letter-spacing:-.2px}.mbh p{margin:1px 0 0;font-size:12px;opacity:.9}.mbh .sp{flex:1;min-width:0}' +
    '.mbhb{display:flex;align-items:center;gap:8px;flex:none;align-self:center}.mbh button{box-sizing:border-box;flex:0 0 32px;width:32px;height:32px;min-width:32px;min-height:32px;margin:0;padding:0;line-height:0;border-radius:10px;border:0;background:rgba(255,255,255,.18);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;position:static;transform:none}.mbh button svg{display:block;width:16px;height:16px;flex:none}.mbh button:hover{background:rgba(255,255,255,.32)}' +
    '.mbmsgs{flex:1;overflow:auto;padding:14px 14px 6px;display:flex;flex-direction:column;gap:10px;background:var(--bg);scroll-behavior:smooth}' +
    '.mbr{display:flex;gap:8px;align-items:flex-end;animation:mbMsg .3s both}.mbr.me{justify-content:flex-end}@keyframes mbMsg{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}' +
    '.mbr .ma{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#46e8dd,#288a84);display:grid;place-items:center;flex:none}.mbr .ma svg{width:26px;height:26px}' +
    '.mbb{max-width:84%;padding:10px 13px;border-radius:16px;font-size:14px;line-height:1.45;background:var(--card);border:1px solid var(--line);border-bottom-left-radius:5px;overflow-wrap:anywhere}' +
    '.mbr.me .mbb{background:linear-gradient(135deg,#2cdace,#28c1b6);color:#fff;border:0;border-radius:16px;border-bottom-right-radius:5px}' +
    '.mbb a{color:var(--accent);font-weight:700}.mbb b{font-weight:700}.mbs{font-size:12px;color:var(--muted)}.mbb a.mbnum{text-decoration:underline dotted;text-underline-offset:3px;cursor:pointer}.mbb a.mbnum:hover{background:color-mix(in srgb,#2cdace 15%,transparent);border-radius:4px}.mbn{display:inline-block;min-width:26px;text-align:center;background:color-mix(in srgb,#2cdace 15%,transparent);color:var(--accent);border-radius:8px;font-weight:800;font-size:12px;padding:0 6px;margin-left:4px}' +
    '.mbl2{margin:7px 0 2px;padding-left:18px}.mbl2 li{margin:3px 0}' +
    '.mbcard{display:grid;grid-template-columns:1fr 1fr;gap:6px 10px;margin-top:9px;padding:10px;border-radius:12px;background:color-mix(in srgb,#2cdace 7%,transparent);font-size:12.5px}.mbcard div{min-width:0}.mbcard span{display:block;color:var(--muted);font-size:11px}.mbcard b{display:block;font-weight:700;overflow-wrap:anywhere}.mbcard .wide{grid-column:1/-1}.mbcard i{display:block;color:var(--muted);font-size:11px;font-style:normal}' +
    '.mbchg{margin:8px 0 2px;padding:9px 11px;border-radius:12px;background:color-mix(in srgb,#f59e0b 13%,transparent);border:1px dashed color-mix(in srgb,#f59e0b 55%,transparent);font-size:13px}.mbchg div{margin:3px 0}.mbchg s{color:var(--muted)}.mbchg em{font-style:normal;font-weight:800;color:#0b7a46}' +
    '.mbact{display:flex;flex-wrap:wrap;gap:6px;margin-top:9px}.mbact button,.mbch button{border:1.5px solid color-mix(in srgb,#2cdace 40%,transparent);background:var(--card);color:var(--accent);border-radius:999px;padding:6px 12px;font:inherit;font-size:12.5px;font-weight:700;cursor:pointer;transition:transform .15s,background .15s}.mbact button:hover,.mbch button:hover{background:color-mix(in srgb,#2cdace 14%,var(--card));transform:translateY(-1px)}' +
    '.mbact button.go{background:linear-gradient(135deg,#16a34a,#15803d);border-color:transparent;color:#fff}.mbact button.no{border-color:color-mix(in srgb,#e11d48 50%,transparent);color:#e11d48}.mbact button:disabled{opacity:.5;cursor:default;transform:none}' +
    '.mbch:empty{display:none}.mbch{display:flex;flex-wrap:wrap;gap:6px;padding:6px 14px 2px;background:var(--bg)}' +
    '.mbty{display:flex;gap:4px;padding:12px 14px}.mbty i{width:7px;height:7px;border-radius:50%;background:#75e8e0;animation:mbDot 1s infinite}.mbty i:nth-child(2){animation-delay:.15s}.mbty i:nth-child(3){animation-delay:.3s}@keyframes mbDot{0%,60%,100%{transform:none;opacity:.5}30%{transform:translateY(-5px);opacity:1}}' +
    '.mbf{display:flex;gap:8px;padding:10px 12px 12px;background:var(--card);border-top:1px solid var(--line)}.mbf input{flex:1;min-width:0;padding:11px 14px;font:inherit;font-size:14px;color:var(--text);background:var(--bg);border:1.5px solid var(--line);border-radius:14px;outline:0}.mbf input:focus{border-color:#2cdace;box-shadow:0 0 0 3px rgba(44,218,206,.16)}' +
    '.mbf .mbmic{background:var(--bg);color:#12877f;border:1.5px solid var(--line);display:grid;place-items:center;padding:0;transition:background .2s,color .2s}.mbf .mbmic svg{width:20px;height:20px}.mbf .mbmic:hover{border-color:#2cdace}.mbf .mbmic[hidden]{display:none}' +
    '.mbf .mbmic.rec{background:#e11d48;color:#fff;border-color:#e11d48;animation:mbRec 1.2s ease-out infinite}@keyframes mbRec{0%{box-shadow:0 0 0 0 rgba(225,29,72,.5)}100%{box-shadow:0 0 0 12px rgba(225,29,72,0)}}.mbf input.lis{border-color:#e11d48;background:color-mix(in srgb,#e11d48 6%,var(--bg))}' +
    '.mbf button{width:44px;border:0;border-radius:14px;background:linear-gradient(135deg,#2cdace,#28c1b6);color:#fff;font-size:18px;cursor:pointer}.mbf button:disabled{opacity:.5}' +
    '.mbhead svg .mbhap,.mbhead svg .mbm2x{display:none}.mbhb #mbSpk.on{background:rgba(255,255,255,.55);color:#0d5c56}' +
    /* face moods */
    '.mbx{overflow:visible}.mbx .mbe{transform-box:fill-box;transform-origin:center;animation:mbBlink 5s infinite}@keyframes mbBlink{0%,92%,100%{transform:scaleY(1)}95%{transform:scaleY(.1)}}' +
    '.mbx .mbp{transition:transform .25s}.mbx .mbhap{display:none;fill:none;stroke:#2a1a12;stroke-width:5;stroke-linecap:round}.mbx .mbbr{fill:none;stroke:#5a3118;stroke-width:5;stroke-linecap:round;transform-box:fill-box;transform-origin:center;transition:transform .3s}' +
    '.mbx .mbm{fill:none;stroke:#6b3f22;stroke-width:3.5;stroke-linecap:round;display:none}.mbx .mbm1{display:block}.mbx .mbm4,.mbx .mbm5,.mbx .mbt,.mbx .mbq{display:none}.mbx .mbt{fill:#60a5fa;opacity:.9}' +
    '.mbx .mbbody{transform-box:fill-box;transform-origin:50% 100%;animation:mbBob 4.5s ease-in-out infinite}@keyframes mbBob{0%,100%{transform:translateY(0)}50%{transform:translateY(-2px) rotate(-1.2deg)}}' +
    '.mbx.think .mbp{transform:translate(-3px,-5px)}.mbx.think .mbbrr{transform:translateY(-6px)}' +
    '.mbx.happy .mbe,.mbx.happy .mbp{display:none}.mbx.happy .mbhap{display:block}.mbx.happy .mbm1{display:none}.mbx.happy .mbm4,.mbx.happy .mbm5{display:block}.mbx.happy .mbbody{animation:mbJump .4s cubic-bezier(.3,0,.4,1) 4 alternate}@keyframes mbJump{from{transform:translateY(0) rotate(-3deg)}to{transform:translateY(-10px) rotate(3deg)}}' +
    '.mbx.cry .mbm1{display:none}.mbx.cry .mbm2{display:block}.mbx.cry .mbbl{transform:translateY(3px) rotate(-14deg)}.mbx.cry .mbbrr{transform:translateY(3px) rotate(14deg)}.mbx.cry .mbt{display:block;animation:mbTear 1.1s ease-in infinite}.mbx.cry .mbt2{animation-delay:.35s}.mbx.cry .mbbody{animation:mbSob .5s ease-in-out infinite alternate}' +
    '@keyframes mbTear{0%{transform:translateY(-6px) scale(.6);opacity:0}25%{opacity:1}100%{transform:translateY(34px) scale(1);opacity:0}}@keyframes mbSob{from{transform:translateY(0)}to{transform:translateY(2px) rotate(1.4deg)}}' +
    '.mbx.conf .mbm1{display:none}.mbx.conf .mbm3{display:block}.mbx.conf .mbbl{transform:translateY(-7px) rotate(-8deg)}.mbx.conf .mbp{transform:translate(4px,-1px)}.mbx.conf .mbq{display:block;animation:mbQ 1.4s ease-in-out infinite;transform-box:fill-box;transform-origin:center}@keyframes mbQ{0%,100%{transform:rotate(-10deg) scale(.9)}50%{transform:rotate(10deg) scale(1.15)}}' +
    '.mbx.scared .mbm1{display:none}.mbx.scared .mbm4{display:block}.mbx.scared .mbbl{transform:translateY(-8px) rotate(-12deg)}.mbx.scared .mbbrr{transform:translateY(-8px) rotate(12deg)}.mbx.scared .mbp{transform:scale(.55)}.mbx.scared .mbe{animation:none}.mbx.scared .mbbody{animation:mbShake .12s linear infinite alternate}@keyframes mbShake{from{transform:translateX(-2px) rotate(-2deg)}to{transform:translateX(2px) rotate(2deg)}}' +
    '.mbx.angry .mbm1{display:none}.mbx.angry .mbm2{display:block}.mbx.angry .mbbl{transform:translateY(7px) rotate(20deg)}.mbx.angry .mbbrr{transform:translateY(7px) rotate(-20deg)}.mbx.angry .mbp{transform:translateY(2px)}.mbx.angry .mbe{animation:none;transform:scaleY(.72)}.mbx.angry .mbbr{stroke:#3a1608;stroke-width:6.5}.mbx.angry .mbbody{animation:mbHuff .35s ease-in-out infinite alternate}@keyframes mbHuff{from{transform:scale(1)}to{transform:scale(1.05,.97)}}' +
    '.mbl.hasq{animation:mbWig 2.4s 2s ease-in-out infinite}@keyframes mbWig{0%,100%{transform:none}8%{transform:rotate(-10deg)}16%{transform:rotate(10deg)}24%{transform:rotate(-6deg)}32%{transform:none}}' +
    '@media(max-width:600px){.mbp2{right:0;left:0;bottom:0;top:0;width:auto;max-width:none;height:auto;border-radius:0}.mbl{right:14px;bottom:14px}.mbl.open{display:none}}' +
    '@media(prefers-reduced-motion:reduce){.mbx *,.mbl,.mbp2,.mbr{animation:none!important}}';

  const st = document.createElement('style'); st.textContent = css; document.head.appendChild(st);
  const wrap = document.createElement('div');
  wrap.innerHTML =
    '<svg width="0" height="0" style="position:absolute" aria-hidden="true"><defs><symbol id="mbface" viewBox="0 0 200 190"><circle cx="36" cy="106" r="22" fill="#8a5632"/><circle cx="36" cy="106" r="12" fill="#f0c7a0"/><circle cx="164" cy="106" r="22" fill="#8a5632"/><circle cx="164" cy="106" r="12" fill="#f0c7a0"/><circle cx="100" cy="102" r="64" fill="#9a6338"/><path d="M100 72c-14-14-46-6-46 24 0 14 6 22 14 28 4 18 18 30 32 30s28-12 32-30c8-6 14-14 14-28 0-30-32-38-46-24z" fill="#f4d5b0"/><ellipse cx="78" cy="102" rx="13" ry="15" fill="#fff"/><circle cx="78" cy="104" r="7" fill="#2a1a12"/><ellipse cx="122" cy="102" rx="13" ry="15" fill="#fff"/><circle cx="122" cy="104" r="7" fill="#2a1a12"/><ellipse cx="100" cy="127" rx="9" ry="6" fill="#6b3f22"/><path d="M84 141q16 14 32 0" fill="none" stroke="#6b3f22" stroke-width="3.5" stroke-linecap="round"/></symbol></defs></svg>' +
    '<button class="mbl hasq" id="mbOpen" type="button" aria-label="Open Pranav" aria-expanded="false"><span class="mbtip">Ask Pranav</span><svg class="mbx" id="mbLaunch" viewBox="0 0 200 190" aria-hidden="true">' + FACE + '</svg></button>' +
    '<section class="mbp2" id="mbPanel" role="dialog" aria-label="Pranav chat" hidden>' +
    '<div class="mbh mbhead"><div class="av"><svg class="mbx" id="mbHead" viewBox="0 0 200 190" aria-hidden="true">' + FACE + '</svg></div><div class="sp"><h3>Pranav</h3><p id="mbSub">Pranav Pipeline’s own assistant · ask me anything</p></div><div class="mbhb"><button type="button" id="mbSpk" title="Read replies aloud: only when I speak" aria-label="Read replies aloud" aria-pressed="false"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5v5h3.5L12 18.5v-13L7.5 9.5z"/><path d="M15.5 9a4 4 0 0 1 0 6M18 6.5a8 8 0 0 1 0 11"/></svg></button><button type="button" id="mbClear" title="Clear chat" aria-label="Clear chat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20.5 12a8.5 8.5 0 1 1-2.5-6"/><path d="M18.5 2.5V7H14"/></svg></button><button type="button" id="mbClose" title="Close" aria-label="Close"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg></button></div></div>' +
    '<div class="mbmsgs" id="mbMsgs" aria-live="polite"></div><div class="mbch" id="mbChips"></div>' +
    '<form class="mbf" id="mbForm" autocomplete="off"><input id="mbIn" type="text" maxlength="400" placeholder="Ask me anything about the deals…" aria-label="Message Pranav"><button type="button" class="mbmic" id="mbMic" aria-label="Speak your question" title="Speak your question"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="3" width="6" height="11" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v3M8.5 21h7"/></svg></button><button type="submit" aria-label="Send">➤</button></form></section>';
  document.body.appendChild(wrap);

  const msgs = g('mbMsgs'), chips = g('mbChips'), input = g('mbIn'), panel = g('mbPanel'), openB = g('mbOpen');
  const WHO = () => { let n = ''; try { n = String((PROFILE && PROFILE.name) || '').trim(); } catch (e) {} return n || String(ME || '').replace(/_/g, ' ').trim(); };
  const S = {deal: null, pending: null, edit: null, busy: false, asked: ''};
  function mood(m, hold) {
    const hd = g('mbHead'); hd.setAttribute('class', 'mbx' + (m && m !== 'idle' ? ' ' + m : ''));
    clearTimeout(mood.t); if (m && m !== 'idle' && m !== 'think') mood.t = setTimeout(() => hd.setAttribute('class', 'mbx'), hold || 7000);
  }
  function scroll() { msgs.scrollTop = msgs.scrollHeight; }
  function addBot(h, opts) {
    opts = opts || {};
    const d = document.createElement('div'); d.className = 'mbr';
    d.innerHTML = '<div class="ma"><svg viewBox="0 0 200 190" aria-hidden="true"><use href="#mbface"/></svg></div><div class="mbb">' + h + '</div>';
    msgs.appendChild(d); scroll(); return d;
  }
  function addMe(t) {
    talked = true;
    const d = document.createElement('div'); d.className = 'mbr me'; d.innerHTML = '<div class="mbb">' + E(t) + '</div>'; msgs.appendChild(d); scroll();
  }
  let talked = false;
  function setChips(list) {
    if (talked && !(S.pending || S.edit) && !(list || []).some(c => c === 'Yes' || c === 'Cancel' || c === 'Tomorrow')) list = [];
    chips.innerHTML = (list || []).map(c => '<button type="button" data-q="' + E(c) + '">' + E(c) + '</button>').join('');
  }
  const baseChips = () => ['Deals created this month', 'Latest deal created', 'My last deal', 'Overdue follow-ups', 'What can you do?'];
  function dealChips() { return S.deal ? ['Who worked last?', 'When was it created?', 'Edit this deal', 'Next follow-up?'] : baseChips(); }
  function actionsFor(r) {
    return '<div class="mbact"><button type="button" data-act="edit" data-id="' + E(r.ID) + '">✎ Edit this deal</button><button type="button" data-act="form" data-id="' + E(r.ID) + '">Open edit form</button></div>';
  }
  function respond(rep) {
    if (!rep) return;
    let h = rep.h || E(rep.t || '');
    if (rep.deal && rep.actions) { S.deal = rep.deal; h += actionsFor(rep.deal); }
    if (rep.copy) track('bot_miss', S.asked);
    if (rep.copy) h += '<div class="mbact"><button type="button" data-act="copyq">📋 Copy my question for the admin</button></div>';
    const d0 = addBot(h);
    try { if (window.PPSound && window.PPSound.on) { window.PPSound.play('pop'); if (S.speakNext || window.PPSound.all) { S.speakNext = false; window.PPSound.speak(h, rep.mood); } } } catch (e) {}
    mood(rep.mood || 'idle');
    g('mbSub').textContent = rep.mood === 'cry' ? 'Sniff… I need more training 😭' : rep.mood === 'conf' ? 'Hmm? That is not my jungle 🙈' : 'Pranav Pipeline’s own assistant · ask me anything';
    setChips(rep.chips || dealChips());
  }
  async function think(fn, ms) {
    S.busy = true; mood('think');
    const ty = document.createElement('div'); ty.className = 'mbr'; ty.innerHTML = '<div class="ma"><svg viewBox="0 0 200 190"><use href="#mbface"/></svg></div><div class="mbb mbty"><i></i><i></i><i></i></div>'; msgs.appendChild(ty); scroll();
    const t0 = Date.now();
    let rep;
    try { rep = await fn(); } catch (e) { rep = {h: 'Oops, I tripped over a vine: ' + E(e && e.message ? e.message : 'something went wrong') + '. Nothing was changed.', mood: 'cry'}; }
    const wait = Math.max(0, (ms || 450) - (Date.now() - t0));
    await new Promise(r => setTimeout(r, wait));
    ty.remove(); S.busy = false; respond(rep);
  }

  /* ---------- confirm / edit flow ---------- */
  function showWho(list, then) {
    S.pending = {t: 'which', then: then, list: list.map(x => x.r)};
    const shown = S.pending.list.slice(0, 8);
    const h = 'I found <b>' + list.length + '</b> students that match. Which one do you mean?' +
      '<div class="mbact">' + shown.map(r => '<button type="button" data-pick="' + E(r.ID) + '">' + E(nameOf(r)) + ' · #' + E(r.ID) + (uniOf(r) !== UNASSIGNED ? ' · ' + E(uniOf(r)) : '') + '</button>').join('') + '</div>' +
      (list.length > 8 ? '<div class="mbs" style="margin-top:6px">Showing 8 of ' + list.length + '. Type the full name or deal number to narrow it down.</div>' : '');
    return {h: h, mood: 'conf', chips: ['Cancel']};
  }
  function describeEdit(E_) {
    const r = E_.deal;
    let h = 'Please check, this is what I understood:<div class="mbchg"><div>👤 <b>' + E(nameOf(r)) + '</b> · #' + E(r.ID) + '</div>' +
      E_.items.map(it => '<div>• ' + E(it.col.label) + ': <s>' + E(it.old || 'empty') + '</s> → <em>' + E(it.v.disp) + '</em></div>').join('') +
      (E_.fu ? '<div>• Next follow-up: <em>' + E(dNice(E_.fu, true)) + '</em></div>' : '') +
      '<div class="mbs" style="margin-top:6px">I will also add this comment: “' + E(E_.comment) + '”</div></div>' +
      '<div class="mbact"><button type="button" class="go" data-act="yes">✅ Yes, save it</button><button type="button" class="no" data-act="no">✖ No, cancel</button></div><div class="mbs" style="margin-top:5px">You can also type yes or no. To fix something, just tell me, for example “make it sem 2” or “follow up on 25 Oct”.</div>';
    return h;
  }
  function buildComment(E_) {
    const who = ME || 'a user';
    const bits = E_.items.map(it => it.col.label + ' changed from “' + (it.old || 'empty') + '” to “' + it.v.disp + '”');
    return ('Pranav (bot): ' + who + ' asked me to update this deal. ' + bits.join('; ') + '.').slice(0, 980);
  }
  function askFollow() {
    S.edit.step = 'fu';
    return {h: 'When should the <b>next follow-up</b> be? (every saved change needs one) 📅<br><span class="mbs">e.g. tomorrow, 20 Oct, in 3 days, next monday 4pm</span>', mood: 'idle', chips: ['Tomorrow', 'In 3 days', 'Next week', 'Cancel']};
  }
  function toConfirm() {
    const e = S.edit; e.comment = buildComment(e); e.step = 'confirm';
    return {h: describeEdit(e), mood: 'idle', chips: ['Yes', 'No']};
  }
  function startEdit(deal, parsed, U) {
    const ed = colsEd(), fuCol = colByRole('fuupd');
    const items = []; const msgsOut = [];
    parsed.items.forEach(it => {
      const c = it.col;
      if (fuCol && c.key === fuCol.key) { return; }
      if (!ed.some(x => x.key === c.key)) { msgsOut.push('I can’t edit <b>' + E(c.label) + '</b> with your login (read-only).'); return; }
      let old = String(deal[c.key] || '');
      if (!old && /^semstat/.test(c.role || '')) old = 'Not Paid';
      const same = norm(old) === norm(it.v.disp);
      if (same) { msgsOut.push('<b>' + E(c.label) + '</b> is already <b>' + E(old || it.v.disp) + '</b>.'); return; }
      items.push({col: c, v: it.v, old: old});
    });
    let fu = null;
    const fi = parsed.items.find(it => fuCol && it.col.key === fuCol.key);
    if (fi) { const d = nlDate(U.raw); if (d) fu = d; }
    else if (/\bfollow\b/.test(U.s)) { const d = nlDate(U.raw); if (d) fu = d; }
    if (!items.length) {
      return {h: (msgsOut.length ? msgsOut.join('<br>') + '<br>' : '') + 'Nothing to change then. 🍌', mood: 'idle'};
    }
    S.deal = deal;
    S.edit = {deal: deal, items: items, fu: fu, step: 'fu', note: msgsOut};
    const lead = msgsOut.length ? msgsOut.join('<br>') + '<br>' : '';
    const rep = fu ? toConfirm() : askFollow();
    rep.h = lead + rep.h;
    return rep;
  }
  async function executeEdit() {
    const e = S.edit; if (!e) return {t: 'There is nothing waiting to be saved.'};
    const r = e.deal, changes = {}, original = {};
    e.items.forEach(it => { changes[it.col.key] = it.v.send; original[it.col.key] = r[it.col.key] || ''; });
    const fuCol = colByRole('fuupd');
    if (fuCol && e.fu) { changes[fuCol.key] = dIsoT(e.fu); original[fuCol.key] = r[fuCol.key] || ''; }
    let by = ''; try { by = localStorage.getItem('pranav_cm_by') || ''; } catch (x) {}
    by = ((by || ME || 'user') + ' via Pranav (bot)').slice(0, 60);
    let resp, j = {};
    try {
      resp = await fetch('api.php?action=update', {method: 'POST', credentials: 'same-origin', headers: {'X-CSRF-Token': CSRF, 'Content-Type': 'application/json'}, body: JSON.stringify({id: r.ID, changes: changes, original: original, comment: cmSrc ? e.comment : '', by: by})});
      if (resp.status === 401) { location.reload(); return {t: 'Your session ended. Please log in again.'}; }
      j = await resp.json().catch(() => ({}));
    } catch (x) { resp = null; }
    if (resp && resp.ok && j.ok) {
      applyRowUpdates(r, j.updated || {});
      TOUCHED[r.ID] = Date.now(); BOT_IDS[r.ID] = Date.now(); evAdd('Pranav saved changes to ' + nameOf(r)); ACT = null;
      try { renderRoute(false); } catch (x) {}
      S.edit = null;
      return {h: '🎉 <b>Done!</b> Saved to Bitrix24 for <b>' + E(nameOf(r)) + '</b>.' + (cmSrc ? '<div class="mbs" style="margin-top:4px">Comment added: “' + E(e.comment) + '”</div>' : '') + dealCard(r), mood: 'happy', deal: r, actions: true};
    }
    let msg = !resp ? 'I could not reach the server. Nothing was saved.' : (j.error || ('Server error ' + resp.status + '. Nothing was saved.'));
    if (resp && resp.status === 409 && j.conflicts) {
      const parts = Object.keys(j.conflicts).map(k => j.conflicts[k].label + ' is now “' + (j.conflicts[k].live || 'empty') + '”');
      msg += ' Changed in Bitrix24 meanwhile: ' + parts.join('; ') + '. I reloaded the latest values, please tell me again.';
      applyRowUpdates(r, j.fresh || {}); try { renderRoute(false); } catch (x) {}
    }
    S.edit = null;
    return {h: '😢 <b>Not saved.</b> ' + E(msg), mood: 'cry'};
  }

  /* ================= v2: periods, filters, reports, my work, knowledge ================= */
  const TWL = ['today', 'yesterday', 'tomorrow', 'week', 'month', 'year', 'this', 'last', 'next', 'past', 'previous', 'days', 'weeks', 'months', 'years'];
  const twFix = t => { if (TWL.indexOf(t) >= 0 || t.length < 4) return t; for (let i = 0; i < TWL.length; i++) if (near(t, TWL[i])) return TWL[i]; return t; };
  const MABBR = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
  const startDay = d => new Date(d.getFullYear(), d.getMonth(), d.getDate());
  const addDays = (d, n) => new Date(d.getFullYear(), d.getMonth(), d.getDate() + n);
  const isTimeWord = t => { const x = twFix(t); return TWL.indexOf(x) >= 0 || MONTHS.indexOf(x) >= 0 || (t.length >= 3 && MABBR.indexOf(t.slice(0, 3)) >= 0 && MONTHS[MABBR.indexOf(t.slice(0, 3))].indexOf(t) === 0) || /^(19|20)\d\d$/.test(t) || /^\d{1,2}(st|nd|rd|th)$/.test(t); };
  function period(raw, futureOk) {
    const low = norm(raw), T = low.split(' ').map(twFix), s = T.join(' ');
    const now = new Date(), t0 = startDay(now);
    let m;
    if ((m = /\b(?:last|past|previous) (\d+) (day|days|week|weeks|month|months)\b/.exec(s))) {
      const n = +m[1], u = m[2][0];
      const a = u === 'd' ? addDays(t0, -n + 1) : u === 'w' ? addDays(t0, -7 * n + 1) : new Date(t0.getFullYear(), t0.getMonth() - n, t0.getDate());
      return {a: a, b: addDays(t0, 1), label: 'in the last ' + n + ' ' + m[2]};
    }
    if (/\btoday\b/.test(s)) return {a: t0, b: addDays(t0, 1), label: 'today'};
    if (/\byesterday\b/.test(s)) return {a: addDays(t0, -1), b: t0, label: 'yesterday'};
    if (/\btomorrow\b/.test(s)) return {a: addDays(t0, 1), b: addDays(t0, 2), label: 'tomorrow'};
    const mon = addDays(t0, -((t0.getDay() + 6) % 7)), y = t0.getFullYear(), mo = t0.getMonth();
    if (/\bthis week\b/.test(s)) return {a: mon, b: addDays(mon, 7), label: 'this week'};
    if (/\b(?:last|previous) week\b/.test(s)) return {a: addDays(mon, -7), b: mon, label: 'last week'};
    if (/\bnext week\b/.test(s)) return {a: addDays(mon, 7), b: addDays(mon, 14), label: 'next week'};
    if (/\bthis month\b/.test(s)) return {a: new Date(y, mo, 1), b: new Date(y, mo + 1, 1), label: 'this month'};
    if (/\b(?:last|previous) month\b/.test(s)) return {a: new Date(y, mo - 1, 1), b: new Date(y, mo, 1), label: 'last month'};
    if (/\bnext month\b/.test(s)) return {a: new Date(y, mo + 1, 1), b: new Date(y, mo + 2, 1), label: 'next month'};
    if (/\bthis year\b/.test(s)) return {a: new Date(y, 0, 1), b: new Date(y + 1, 0, 1), label: 'this year'};
    if (/\b(?:last|previous) year\b/.test(s)) return {a: new Date(y - 1, 0, 1), b: new Date(y, 0, 1), label: 'last year'};
    // dd/mm[/yyyy]
    if ((m = /\b(\d{1,2})\/(\d{1,2})(?:\/(\d{2,4}))?\b/.exec(low))) {
      let yy = m[3] ? +m[3] : y; if (yy < 100) yy += 2000;
      const d = new Date(yy, +m[2] - 1, +m[1]);
      if (!isNaN(d)) return {a: d, b: addDays(d, 1), label: 'on ' + dNice(d)};
    }
    // month names (with optional day / year)
    for (let j = 0; j < T.length; j++) {
      const t = T[j];
      let k = MONTHS.indexOf(t);
      if (k < 0 && t === 'sept') k = 8;
      if (k < 0 && t.length === 3) k = MABBR.indexOf(t);
      if (k < 0) continue;
      const prev = T[j - 1] || '', next = T[j + 1] || '';
      const dayAdj = /^\d{1,2}(st|nd|rd|th)?$/.test(prev) ? parseInt(prev, 10) : /^\d{1,2}(st|nd|rd|th)?$/.test(next) && !/^(19|20)\d\d$/.test(next) ? parseInt(next, 10) : 0;
      const yr = /^(19|20)\d\d$/.test(next) ? +next : /^(19|20)\d\d$/.test(prev) ? +prev : 0;
      if ((t === 'may' || t === 'mar') && !yr && !dayAdj && !/^(in|of|during|for|since|on|created|added|from)$/.test(prev)) continue;
      let yy = yr || y;
      if (dayAdj) { let d = new Date(yy, k, dayAdj); if (!yr && !futureOk && d > now) d = new Date(yy - 1, k, dayAdj); return {a: d, b: addDays(d, 1), label: 'on ' + dNice(d)}; }
      let a = new Date(yy, k, 1);
      if (!yr && !futureOk && a > now) a = new Date(yy - 1, k, 1);
      return {a: a, b: new Date(a.getFullYear(), a.getMonth() + 1, 1), label: 'in ' + MONTHS[k].replace(/^./, c => c.toUpperCase()) + ' ' + a.getFullYear()};
    }
    return null;
  }
  const inP = (d, p) => !!d && d >= p.a && d < p.b;
  const createdOf = r => parseStored(r.DATE_CREATE);
  const fuOf = r => parseStored(val(r, 'fuview') || val(r, 'fuupd'));
  const dealLink = r => '<a href="#" data-deal="' + E(r.ID) + '">' + E(nameOf(r)) + '</a> <span class="mbs">#' + E(r.ID) + (uniOf(r) !== UNASSIGNED ? ' · ' + E(uniOf(r)) : '') + '</span>';
  const timeFree = U => {
    const T = [], C = [];
    U.T.forEach((t, i) => { if (!isTimeWord(t) && !/^(new|newest|newly|recent|recently|latest|oldest|earliest)$/.test(t)) { T.push(t); C.push(U.C[i]); } });
    return {T: T, C: C};
  };
  function valueFilters(U) {
    const tf = timeFree(U);
    const F = parseFilters(tf.T, tf.C, {});
    return F;
  }

  /* ---- counts / lists for a period ---- */
  function periodReply(U, raw) {
    const p0 = period(raw, true);
    if (!p0) return null;
    const fu = /\bfollow\b/.test(U.s) || p0.a > new Date();
    const p = fu ? p0 : period(raw, false);
    if (!p) return null;
    const F = valueFilters(U);
    const list = allRows.filter(r => F.every(f => f.test(r)) && inP(fu ? fuOf(r) : createdOf(r), p));
    list.sort((a, b) => ((fu ? fuOf(a) : createdOf(a)) - (fu ? fuOf(b) : createdOf(b))) * (fu ? 1 : -1));
    const n = list.length;
    const what = fu ? 'with a follow-up due ' : 'created ';
    let h = '<b>' + numLink(list, 'Deals ' + what + p.label) + '</b> deal' + (n === 1 ? '' : 's') + (F.length ? filtText(F) : '') + ' ' + what + '<b>' + E(p.label) + '</b>' + (p.b - p.a > 864e5 * 2 ? ' <span class="mbs">(' + dNice(p.a) + ' – ' + dNice(addDays(p.b, -1)) + ')</span>' : '') + '.';
    if (n) h += list_(list.slice(0, 6).map(r => dealLink(r) + ' <span class="mbs">' + E(niceStored(fu ? (val(r, 'fuview') || val(r, 'fuupd')) : r.DATE_CREATE)) + '</span>')) + (n > 6 ? '<div class="mbs">…and ' + (n - 6) + ' more.</div>' : '');
    else h += '<div class="mbs">Nothing there. 🍌</div>';
    return {h: h, mood: n ? 'happy' : 'conf', chips: baseChips()};
  }

  /* ---- latest / oldest deal ---- */
  function latestReply(U, raw) {
    const low = norm(raw), s = U.s;
    if (period(raw, true) || /\b(least|fewest|lowest|smallest|most|highest|biggest)\b/.test(low)) return null;
    const old = /\b(oldest|earliest)\b/.test(low);
    const isLast = /\b(last|lastest|latest|newest|newly|recent|recently|new)\b/.test(low);
    if (!(old || isLast)) return null;
    if (!/\bdeals?\b|\bleads?\b|\badmissions?\b|\brecords?\b|\bentry\b|\bentries\b/.test(s + ' ' + low)) return null;
    if (/\b(worked|comment|follow|edit|edited|updated|touched|contacted)\b/.test(s) || /\b(i|my|me|mine)\b/.test(low)) return null;
    const F = valueFilters(U);
    const list = allRows.filter(r => F.every(f => f.test(r)) && createdOf(r));
    if (!list.length) return {h: 'I cannot see any created dates yet. 🙈', mood: 'conf'};
    list.sort((a, b) => (createdOf(b) - createdOf(a)) * (old ? -1 : 1));
    const nm = /\b(\d{1,2})\b/.exec(low), plural = /\bdeals\b|\bstudents\b|\bleads\b/.test(low);
    const n = nm ? Math.min(+nm[1], 15) : (plural && /\b(few|some|recent|latest|newest)\b/.test(low) ? 5 : 1);
    if (n > 1) {
      return {h: 'The ' + (old ? 'oldest' : 'latest') + ' <b>' + n + '</b> deals' + (F.length ? filtText(F) : '') + ':' + list_(list.slice(0, n).map(r => dealLink(r) + ' <span class="mbs">' + E(niceStored(r.DATE_CREATE)) + ' (' + agoDays(createdOf(r)) + ')</span>')), mood: 'happy'};
    }
    const r = list[0];
    return {h: 'The ' + (old ? 'oldest' : 'latest') + ' deal' + (F.length ? filtText(F) : '') + ' is <b>' + E(nameOf(r)) + '</b> (#' + E(r.ID) + '), created <b>' + E(niceStored(r.DATE_CREATE)) + '</b> (' + agoDays(createdOf(r)) + ').' + dealCard(r), mood: 'happy', deal: r, actions: true};
  }

  /* ---- what I worked on ---- */
  async function myItems() {
    const act = (await activity()).filter(e => e && e.user === ME && e.type !== 'login' && e.deal && e.changes);
    act.sort((a, b) => String(b.at).localeCompare(String(a.at)));
    return act;
  }
  const whenTxt = at => { const d = new Date(at); return isNaN(d) ? String(at) : dNice(d, true) + ' (' + agoDays(d) + ')'; };
  async function workReply(U, raw) {
    const low = norm(raw), s = U.s;
    if (!/\b(i|my|mine|ive|i have|myself)\b/.test(low)) return null;
    if (!(/\b(worked|work)\b/.test(s) || /\b(saved|opened|did|done|handled|edit|edited|my work|my activity|my edits|my changes)\b/.test(low))) return null;
    if (U.hasSetVerb && fieldsIn(U.s, U.s).length) return null;
    if (/\bfilter/.test(low)) return null;
    const items = await myItems();
    const byDeal = [];
    const seen = {};
    items.forEach(e => { if (!seen[e.deal]) { seen[e.deal] = 1; byDeal.push(e); } });
    const p = period(raw, false);
    if (/\b(how many)\b/.test(low) || /\bcount\b|\bnumber of\b/.test(s)) {
      const inn = p ? items.filter(e => inP(new Date(e.at), p)) : items;
      const dd = {}; inn.forEach(e => { dd[e.deal] = 1; });
      return {h: 'You made <b>' + inn.length + '</b> save' + (inn.length === 1 ? '' : 's') + ' on <b>' + Object.keys(dd).length + '</b> deal' + (Object.keys(dd).length === 1 ? '' : 's') + ' from this dashboard' + (p ? ' <b>' + E(p.label) + '</b>' : ' so far') + '.<div class="mbs">I can only see edits saved from this dashboard.</div>', mood: 'happy'};
    }
    if (!byDeal.length) {
      // fall back to comments signed with the user's name
      let best = null;
      const me = String(ME || '').toLowerCase();
      if (me) allRows.forEach(r => { const cm = lastCm(r); if (cm && String(cm.by || '').toLowerCase().indexOf(me) >= 0 && (!best || cm.ms > best.cm.ms)) best = {r: r, cm: cm}; });
      if (best) return {h: 'I have no saved edits from you in the dashboard log, but the latest comment signed by you is on <b>' + E(nameOf(best.r)) + '</b> (#' + E(best.r.ID) + ') at <b>' + E(best.cm.at) + '</b>.' + dealCard(best.r), mood: 'happy', deal: best.r, actions: true};
      return {h: 'I can’t see anything you worked on yet. 🙈 I only know edits saved from this dashboard and comments signed with your name. Once you save a deal, ask me again!', mood: 'conf'};
    }
    const rowOf = id => allRows.find(r => String(r.ID) === String(id));
    const wantUni = /\buniversit|\buni\b|\bcollege\b/.test(s + ' ' + low), wantProg = /\bprogram|\bcourse\b/.test(s + ' ' + low), wantBatch = /\bbatch\b/.test(s);
    const many = /\b(last|recent|latest)\s+(\d{1,2})\b|\b(\d{1,2})\s+(recent|latest|last)\b/.exec(low);
    const dist_ = (fn, label) => {
      const out = [], sn = {};
      byDeal.forEach(e => { const r = rowOf(e.deal); if (!r) return; const v = fn(r); if (v && !sn[v]) { sn[v] = 1; out.push({v: v, r: r, e: e}); } });
      if (!out.length) return '';
      return 'The last ' + label + ' you worked on is <b>' + E(out[0].v) + '</b> <span class="mbs">(deal ' + E(nameOf(out[0].r)) + ', ' + E(whenTxt(out[0].e.at)) + ')</span>.' + (out.length > 1 ? '<div class="mbs">Before that: ' + out.slice(1, 4).map(x => E(x.v)).join(' → ') + '</div>' : '');
    };
    if (wantUni || wantProg || wantBatch) {
      const parts = [];
      if (wantUni) parts.push(dist_(r => uniOf(r) !== UNASSIGNED ? uniOf(r) : '', 'university'));
      if (wantProg) parts.push(dist_(r => val(r, 'program'), 'program'));
      if (wantBatch) parts.push(dist_(r => val(r, 'batch'), 'batch'));
      return {h: parts.filter(Boolean).join('<br>') || 'I could not work that out from your recent edits. 🙈', mood: 'happy'};
    }
    const n = many ? Math.min(+(many[2] || many[3]), 15) : (/\bdeals\b|\bstudents\b|\brecent\b/.test(low) && !/\bdeal\b/.test(low) ? 5 : 1);
    if (n > 1) {
      return {h: 'Your last <b>' + Math.min(n, byDeal.length) + '</b> deals:' + list_(byDeal.slice(0, n).map(e => { const r = rowOf(e.deal); return r ? dealLink(r) + ' <span class="mbs">' + E(whenTxt(e.at)) + '</span>' : 'Deal #' + E(e.deal); })), mood: 'happy'};
    }
    const e = byDeal[0], r = rowOf(e.deal);
    if (!r) return {h: 'Your last save was on deal #' + E(e.deal) + ' at ' + E(whenTxt(e.at)) + ', but I can’t see that deal now.', mood: 'conf'};
    const ch = Object.keys(e.changes || {}).map(k => { const c = e.changes[k]; return E(c.field) + ': <s>' + E(c.old || 'empty') + '</s> → <em>' + E(c.new || 'empty') + '</em>'; });
    return {h: 'The last deal you worked on is <b>' + E(nameOf(r)) + '</b> (#' + E(r.ID) + '), saved <b>' + E(whenTxt(e.at)) + '</b>.' + (ch.length ? '<div class="mbs" style="margin:4px 0">' + ch.join('<br>') + '</div>' : '') + dealCard(r), mood: 'happy', deal: r, actions: true};
  }

  /* ---- dashboard filters (the filter bar) ---- */
  const GF_ID = {uni: 'uni', program: 'prog', batch: 'batch', mode: 'mode', stage: 'stage', team: 'team', sem: 'sem'};
  const GF_LABEL = {uni: 'University', prog: 'Program', batch: 'Batch', mode: 'Mode of payment', stage: 'Stage', team: 'Team Name', sem: 'Current semester'};
  function gfText(gf) {
    const parts = Object.keys(gf || {}).filter(k => gf[k] && gf[k].length).map(k => '<b>' + E(GF_LABEL[k] || k) + '</b>: ' + gf[k].map(E).join(', '));
    return parts.join(' · ');
  }
  const gfNow = () => { const o = {}; try { Object.keys(GF).forEach(k => { if (GF[k] && GF[k].length) o[k] = GF[k].slice(); }); } catch (e) {} return o; };
  let LASTGF = null;
  try { LASTGF = JSON.parse(localStorage.getItem('mb_lastgf') || 'null'); } catch (e) {}
  setInterval(() => {
    const n = gfNow();
    if (Object.keys(n).length && (!LASTGF || JSON.stringify(LASTGF.gf) !== JSON.stringify(n))) {
      LASTGF = {gf: n, at: Date.now()};
      try { localStorage.setItem('mb_lastgf', JSON.stringify(LASTGF)); } catch (e) {}
    }
  }, 1500);
  function valueGroups(T, C, skip) {
    const hits = matchValues(T, C, skip || {});
    const G = {}, used = {};
    hits.forEach(h => {
      const id = GF_ID[h.vd.role]; if (!id) return;
      (G[id] = G[id] || {role: h.vd.role, vals: [], idx: []});
      if (G[id].vals.indexOf(h.vd.v) < 0) G[id].vals.push(h.vd.v);
      h.idx.forEach(i => { used[i] = 1; G[id].idx.push(i); });
    });
    return {G: G, used: used};
  }
  function gfValue(id, v) { return v; }
  function applyGF(next, merge) {
    const base = merge ? gfNow() : {};
    const cur = merge ? base : gfNow();
    Object.keys(next).forEach(id => {
      if (merge && cur[id]) next[id].forEach(v => { if (cur[id].indexOf(v) < 0) cur[id].push(v); });
      else cur[id] = next[id].slice();
    });
    GF = cur; gfSave(); try { page = 0; } catch (e) {}
    if (route.type === 'report' || route.type === 'reports' || route.rb) location.hash = '#/'; else renderRoute(false);
    return cur;
  }
  function filterReply(U, raw) {
    const low = norm(raw), s = U.s;
    if (/\b(clear|remove|reset|cancel|drop|delete|turn off|disable|no)\b.*\bfilters?\b|\bfilters?\b.*\b(clear|remove|reset|off)\b|\bunfilter\b|\bshow (me )?(everything|all deals)\b|\bremove all\b/.test(low)) {
      const had = Object.keys(gfNow()).length;
      GF = {}; gfSave(); try { page = 0; } catch (e) {} if (route.type === 'report' || route.type === 'reports') location.hash = '#/'; else renderRoute(false);
      return {h: had ? 'Done, all dashboard filters are cleared. 🧹 Showing all <b>' + fmtN(allRows.length) + '</b> deals.' : 'No filters were on, so everything is already showing. 🍌', mood: 'happy'};
    }
    if (/\b(what|which|whats|current|last|previous|recent|my|any)\b.*\bfilters?\b|\bfilters?\b.*\b(applied|using|used|worked|set|on|selected|active|now)\b/.test(low) && !/\b(apply|set|use)\b.*\bfilters?\b.*\b(for|to|on)\b/.test(low) && !valueGroups(U.T, U.C, {}).used[0] && !Object.keys(valueGroups(U.T, U.C, {}).G).length) {
      const n = gfNow();
      if (Object.keys(n).length) return {h: 'Right now these filters are on:<div class="mbs" style="margin:4px 0">' + gfText(n) + '</div>Showing <b>' + fmtN(gfRows.length) + '</b> of <b>' + fmtN(allRows.length) + '</b> deals.<div class="mbact"><button type="button" data-act="clearf">🧹 Clear filters</button></div>', mood: 'happy'};
      if (LASTGF && Object.keys(LASTGF.gf || {}).length) return {h: 'No filter is on right now. The last filter I saw you use (' + E(whenTxt(LASTGF.at)) + '):<div class="mbs" style="margin:4px 0">' + gfText(LASTGF.gf) + '</div><div class="mbact"><button type="button" data-act="reapply">↻ Apply it again</button></div>', mood: 'happy'};
      return {h: 'You haven’t used any filter yet that I could see. 🙈 Try “filter muj + smu” or “only mba and bba”.', mood: 'conf'};
    }
    const explicit = /\bfilters?\b|\bfiltered\b|\bapply\b|\bnarrow\b|\bfocus\b|\brestrict\b/.test(low);
    const weak = /\bonly\b|\blimit\b/.test(low);
    const showish = weak || /^(show|display|view|open|switch|go|see)\b/.test(low) || /\b(show me|take me|open|switch to)\b/.test(low);
    if (/\b(how many|count|total|number of|percent|percentage|who|when|status|phone|email|follow|created|worked|list)\b/.test(low) && !explicit) return null;
    if (/\breport\b/.test(low)) return null;
    const VG = valueGroups(U.T, U.C, {});
    const ids = Object.keys(VG.G);
    if (!ids.length) {
      if (explicit && /\bfilters?\b/.test(low)) return {h: 'Tell me what to filter, for example “filter muj + smu + amity”, “only mba and bba” or “filter batch january 2025”.', mood: 'idle', chips: ['Filter by university', 'What filter is on?', 'Clear filters']};
      return null;
    }
    // bare list of values, or explicit/show verb
    const rest = U.T.filter((t, i) => !VG.used[i] && !STOP.has(t) && !LEXV[t] && !isTimeWord(t) && t.length > 2 && !/^(filters?|filtered|only|apply|narrow|focus|restrict|limit|view|display|open|switch|show|see|deals?|students?|and|plus|also|add|include)$/.test(t));
    const bare = !explicit && !showish && U.T.every((t, i) => VG.used[i] || /^(and|or|plus|with|the|a|only|also|deals?|students?|for|of)$/.test(t));
    if (!(explicit || showish || bare)) return null;
    if (showish && !explicit && attrsOf(U.s).length) return null;
    const next = {};
    ids.forEach(id => { next[id] = VG.G[id].vals; });
    // keep only values that exist in the dashboard filter lists
    const defs = fieldDefs(); const fixed = {};
    ids.forEach(id => { const d = defs.find(x => x.id === id); if (!d) return; const have = new Set(allRows.map(r => fv(r, d.key))); const ok = next[id].filter(v => have.has(v)); if (ok.length) fixed[id] = ok; });
    if (!Object.keys(fixed).length) return {h: 'I understood the filter, but that field isn’t on your filter bar. 🙈', mood: 'conf'};
    const add = /\b(also|add|include|along with|as well|too|plus)\b/.test(low) && Object.keys(gfNow()).length;
    applyGF(fixed, !!add);
    const miss = rest.slice(0, 3);
    const total = gfRows.length;
    const dim = gfText(gfNow()).split(' · ').join('<br>');
    return {h: '✅ Filter applied on the dashboard:<div class="mbs" style="margin:4px 0">' + dim + '</div>Showing <b>' + fmtN(total) + '</b> of <b>' + fmtN(allRows.length) + '</b> deals.' + (Object.keys(gfNow()).length > Object.keys(fixed).length ? '<div class="mbs" style="margin-top:4px">Your other filters are still on (they combine). Say “clear filters” to start fresh.</div>' : '') + (miss.length ? '<div class="mbs" style="margin-top:4px">I couldn’t match “' + miss.map(E).join('”, “') + '” to anything, so I ignored it.</div>' : '') + '<div class="mbact"><button type="button" data-nav="#/master/sheet">Open these deals</button><button type="button" data-act="clearf">🧹 Clear filters</button></div>', mood: 'happy', chips: ['What filter is on?', 'Clear filters', 'How many deals?']};
  }

  /* ---- reports ---- */
  const kwHit = (low, words) => low.split(' ').some(t => t.length >= 3 && words.some(w => t === w || (t.length >= 5 && near(t, w))));
  function reportTarget(low) {
    if (kwHit(low, ['percentage', 'percent']) && /\bre ?reg|registration|rereg/.test(low)) return 'rr';
    if (/\bre ?reg|\brereg|re-?registration|registration tracker/.test(low)) return 'reg';
    if (kwHit(low, ['documents', 'document', 'docs'])) return 'docs';
    if (kwHit(low, ['reference', 'references', 'referral', 'referrals'])) return 'ref';
    if (kwHit(low, ['alumni', 'alumnus'])) return 'alumni';
    if (kwHit(low, ['activity', 'audit']) || /\bwho changed|change log|activity log|history of changes/.test(low)) return 'activity';
    if (kwHit(low, ['custom', 'builder'])) return 'custom';
    return null;
  }
  function axisList(low) {
    const T = low.split(' '), out = [];
    const add = (key, label) => { if (key && !out.some(o => o.key === key)) out.push({key: key, label: label}); };
    for (let i = 0; i < T.length; i++) {
      const t = T[i];
      const semN = (/^sem\w*$/.test(t) || near(t, 'semester')) && (/^\d$/.test(T[i + 1] || '') ? +T[i + 1] : /^\d$/.test(T[i - 1] || '') ? +T[i - 1] : 0);
      if (semN && /\b(status|paid|fee|fees|payment)\b/.test(T.slice(i - 1, i + 4).join(' '))) { const c = cols.find(x => x.role === 'semstat' + semN); if (c) { add(c.key, 'Semester ' + semN + ' status'); for (let j = i + 1; j <= i + 3; j++) if (/^(status|statuses|paid|fee|fees|payment)$/.test(T[j] || '')) T[j] = ''; continue; } }
      if (/^(universit\w*|unis?|colleges?|univ)$/.test(t) || near(t, 'university') || near(t, 'universities')) add(uniKey(), 'University');
      else if (/^(programs?|programmes?|courses?)$/.test(t) || near(t, 'programs')) add(progKey(), 'Program');
      else if (/^(batch|batches|intake|intakes)$/.test(t)) add(batchKeyG(), 'Batch');
      else if (/^(mode|modes)$/.test(t) || (t === 'payment' && !/^sem/.test(T[i - 1] || '')) || /^(payment)$/.test(t) && /mode/.test(T[i + 1] || '')) add(modeKey(), 'Mode of payment');
      else if (/^(stage|stages|status|statuses|pipeline)$/.test(t) && !/^sem/.test(T[i - 1] || '')) add('STAGE_ID', 'Stage');
      else if (/^(team|teams)$/.test(t) && !SCOPE) { const c = colByRole('team'); if (c) add(c.key, 'Team'); }
      else if (/^(lead)$/.test(t) && /status|statuses/.test(T[i + 1] || '')) { const c = colByRole('lead'); if (c) { add(c.key, 'Lead status'); T[i + 1] = ''; } }
      else if (/^(semester|sem|sems|semesters)$/.test(t) && !/^\d$/.test(T[i + 1] || '') && !/^\d$/.test(T[i - 1] || '')) add(semKeyCur(), 'Current semester');
    }
    return out;
  }
  function reportReply(U, raw) {
    const low = norm(raw);
    const navVerb = /\b(open|show|take me|go to|goto|view|see|display|navigate|jump)\b/.test(low), tg0 = reportTarget(low);
    const hasReport = tg0 === 'activity' || (navVerb && tg0 && (tg0 !== 'docs' || /pending/.test(low)) && !/\bhow many|count|status of|phone\b/.test(low)) || /\breports?\b|\bpivot\b|\bcross ?tab\b|\bbreakdown\b|\bbreak down\b|\btable of\b/.test(low) || kwHit(low, ['report', 'reports']);
    if (!hasReport) return null;
    if (/\bhow\b.*\b(report|reports)\b.*\b(work|works|use|used)\b/.test(low)) return null;
    const nav = id => REPORTS.some(r => r.id === id);
    const VG = valueGroups(U.T, U.C, {});
    const qp = new URLSearchParams();
    const defs = fieldDefs();
    Object.keys(VG.G).forEach(id => { const d = defs.find(x => x.id === id); if (!d) return; const have = new Set(allRows.map(r => fv(r, d.key))); const ok = VG.G[id].vals.filter(v => have.has(v)); if (ok.length) qp.set(id, ok.join(MS)); });
    const flt = qp.toString();
    const fnote = Object.keys(VG.G).length ? '<div class="mbs">Filters carried over: ' + gfText(Object.fromEntries(Object.keys(VG.G).map(id => [id, VG.G[id].vals]))) + '</div>' : '';
    const create = /\b(create|build|make|generate|prepare|design|new|need|want|draw|give me|get me)\b/.test(low);
    const axes = axisList(low);
    const target = reportTarget(low);
    const list = () => REPORTS.map(r => '<button type="button" data-nav="#/reports/' + E(r.id) + '">' + r.icon + ' ' + E(r.title) + '</button>').join('');
    // build a custom report
    if (create && axes.length && (!target || target === 'custom')) {
      const r1 = axes[0], c1 = axes[1] || (axes[0].key === 'STAGE_ID' ? {key: uniKey(), label: 'University'} : {key: 'STAGE_ID', label: 'Stage'});
      const p = new URLSearchParams(); p.set('r1', r1.key); p.set('c1', c1.key);
      if (axes[2]) p.set('r2', axes[2].key); if (axes[3]) p.set('c2', axes[3].key);
      qp.forEach((v, k) => p.set(k, v));
      const hash = '#/reports/custom?' + p.toString();
      setTimeout(() => { location.hash = hash; }, 250);
      return {h: '🧩 Built it in the <b>Custom report builder</b>:<ul class="mbl2"><li>Down the side: <b>' + E(r1.label) + '</b>' + (axes[2] ? ' → <b>' + E(axes[2].label) + '</b>' : '') + '</li><li>Across the top: <b>' + E(c1.label) + '</b>' + (axes[3] ? ' → <b>' + E(axes[3].label) + '</b>' : '') + '</li></ul>' + fnote + '<div class="mbs">It’s a live view, not a saved report. Bookmark the page to keep it, or press “Download CSV” there.</div>', mood: 'happy', chips: ['Show all reports', 'What filter is on?', 'How many deals?']};
    }
    if (create && !axes.length && !target) {
      return {h: 'Sure! Tell me what to put down the side and across the top, for example:<ul class="mbl2"><li>“create a report of university by stage”</li><li>“make a report of program vs sem 1 status for muj + smu”</li><li>“build a report of batch by mode of payment”</li></ul>Or open one of the ready-made reports:<div class="mbact">' + list() + '</div>', mood: 'idle', chips: ['Create report university by stage', 'Show all reports']};
    }
    if (target && nav(target)) {
      const def = REPORTS.find(r => r.id === target);
      const hash = '#/reports/' + target + (flt ? '?' + flt : '');
      setTimeout(() => { location.hash = hash; }, 250);
      return {h: (create ? 'That report already exists, so I’m taking you there: ' : 'Opening ') + def.icon + ' <b>' + E(def.title) + '</b>…' + fnote + (create ? '<div class="mbs">If you want a different table, say “create a report of X by Y”.</div>' : ''), mood: 'happy', chips: ['Show all reports', 'How many deals?']};
    }
    if (axes.length && !create) {
      // "report of university by stage" without a create verb → still build it
      return reportReply(U, 'create ' + raw);
    }
    setTimeout(() => { location.hash = '#/reports'; }, 250);
    return {h: 'Here are the reports I know. Tap one to open it:<div class="mbact">' + list() + '</div>', mood: 'happy', chips: ['Create report university by stage', 'Documents pending report']};
  }

  /* ---- editing by phone / email: guide, never edit ---- */
  function guideEdit(list, U, how) {
    if (list.length > 1) {
      return {h: 'Oh! Editing is <b>your</b> job, boss. I only swing around the data! This matches <b>' + list.length + '</b> leads. Tap the one you want and I’ll open it for you:<div class="mbact">' + list.slice(0, 8).map(r => '<button type="button" data-act="form" data-id="' + E(r.ID) + '">' + E(nameOf(r)) + ' · #' + E(r.ID) + '</button>').join('') + '</div>', mood: 'happy'};
    }
    const r = list[0];
    let want = [];
    try { const parsed = parseEdit(U, r); want = parsed.items.map(it => '<b>' + E(it.col.label) + '</b> → <em>' + E(it.v.disp) + '</em>'); } catch (e) {}
    const steps = [];
    steps.push('Set ' + (want.length ? want.join(', ') : 'the field you want to change') + '.');
    steps.push('Pick the <b>Next follow-up</b> date.');
    steps.push('Write a <b>comment</b> and your <b>name</b>.');
    steps.push('Press <b>Save</b>.');
    S.edit = null; S.pending = null; S.deal = r; S.via = how || 'name';
    try { openEdit(r); const b = document.getElementById('mBanner'); if (b) { b.textContent = 'Pranav: ' + steps.map((x, i) => (i + 1) + '. ' + x.replace(/<[^>]+>/g, '')).join('  ') ; b.hidden = false; } } catch (e) {}
    setTimeout(() => { try { openPanel(false); } catch (e) {} }, 200);
    return {h: 'Oh! That one’s <b>your work</b>, boss. I only swing around the data! I opened <b>' + E(nameOf(r)) + '</b> (#' + E(r.ID) + ') for you. Quick drill:<ol class="mbl2">' + steps.map(x => '<li>' + x + '</li>').join('') + '</ol>I’ll be right here if you need me. 🍌', mood: 'happy'};
  }

  /* ---- everyday questions & how-tos ---- */
  const WD = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  function kbReply(U, raw) {
    const low = norm(raw), s = U.s;
    const R = (h, mood, chips) => ({h: h, mood: mood || 'happy', chips: chips});
    const t = new Date();
    if (/\b(today'?s? date|date today|what.?s the date|what date|what day|which day|current date|date now|todays date)\b/.test(low)) return R('Today is <b>' + WD[t.getDay()] + ', ' + dNice(t) + '</b>. 📅');
    if (/\b(what time|current time|time now|time is it|whats the time|time please)\b/.test(low)) return R('It’s <b>' + ((t.getHours() % 12) || 12) + ':' + pad(t.getMinutes()) + ' ' + (t.getHours() >= 12 ? 'pm' : 'am') + '</b> on your device. 🕐');
    if (/\b(what.?s (happening|going on|new|up)|what is (happening|going on)|what am i (doing|looking|viewing|seeing)|where am i|what (did|have) i (just )?(do|done|open|opened|change|changed)|any (news|updates?|changes)|what.?s new|are you watching|what are you (seeing|watching)|current page|which page|recent activity|what happened)\b/.test(low)) return R(watchSummary(), 'happy', ['Overdue follow-ups', 'Latest deal created']);
    if (/\b(who am i|my name|who is logged|logged in as|my login|my username|which user|my role|my access|what access|what can i see|which team am i)\b/.test(low)) return R('You’re <b>' + E(ME || 'a user') + '</b>. ' + (SCOPE ? 'This is a team login, so you see only the deals of team <b>' + E(SCOPE) + '</b>' : 'You see <b>all</b> deals') + ' (' + fmtN(allRows.length) + ' loaded).');
    if (/\b(what|which)\b.*\b(fields?|things|columns?)\b.*\b(edit|change|update)\b|\bwhat can (i|you) (edit|change|update)\b|\bwhich fields\b/.test(low)) { const ed = colsEd().map(c => c.label); return R(ed.length ? 'With your login I can edit: ' + list_(ed.map(E)) + '<div class="mbs">Every save needs a next follow-up date, a comment and a name.</div>' : 'Your login is read-only, so I can’t edit anything. 🙈', 'happy'); }
    if (/\bwho (is|s) (the |your |our )?(admin|boss|owner|developer|creator)\b/.test(low)) return R('The <b>admin</b> is the person who set up this dashboard and trained me. If I can’t answer, tell him — I’ll be crying in the corner.');
    if (/\b(who|what)\b.*\b(made|built|created|developed|trained|programmed|designed|owns?|is)\b.*\b(you|ur|your)\b|\bwho is your (admin|boss|maker|creator|developer|owner)\b|\byour (admin|creator|maker|developer)\b/.test(low)) return R('My <b>admin</b> built me and keeps me in the jungle. 🌴 If I don’t know something, it’s his fault — tell him to train me! 🍌');
    if (/\b(are you|r u|u r|you are)\b.*\b(ai|a\.i|chatgpt|gpt|claude|robot|real|human|bot|alive|smart|intelligent|monkey)\b/.test(low)) return R('I’m a <b>rule-based bot</b> — not a human, not a big AI. I read this dashboard’s data and follow rules my admin taught me. I get confused easily, so be nice!');
    if (/\bhow old (are|r) (you|u)\b|\byour age\b|\bwhen were you born|\bwhere do you live|\bwhere are you from|\byour (family|friend|girlfriend|boyfriend|wife|husband)\b/.test(low)) return R('I live inside this dashboard, rent-free, paid in bananas. 🍌 Age: ageless.');
    if (/\b(i love you|love u|luv u|marry me|be my friend|you are cute|ur cute|you are sweet)\b/.test(low)) return R('Aww! 🙈 *blushes* I love you too — but I love clean data even more. 🍌');
    if (/\b(stupid|dumb|idiot|useless|worst bot|bad bot|nonsense|shut up|hate you|fool|donkey)\b/.test(low)) return R('😢 Ouch, that hurt my banana. I’m trying my best! Tell me what I got wrong and the admin will train me.', 'cry');
    if (/\b(good bot|well done|great job|good job|nice work|you are smart|you are clever|clever|smart bot|brilliant|you rock|superb|excellent)\b/.test(low)) return R('Yay! 🎉 Thank you! *does a tiny backflip*');
    if (/\b(sorry|my bad|oops|apologies|pardon)\b/.test(low) && low.split(' ').length <= 4) return R('No worries at all! 🍌 Bots forgive fast.');
    if (/\b(banana|bananas|favourite food|favorite food|do you eat|hungry)\b/.test(low) && !/\bdeal|student|count/.test(low)) return R('Bananas! 🍌🍌🍌 Forget deals, I’d trade ten universities for one ripe banana.');
    if (/\b(what is|whats|what s|explain|tell me about|about|describe)\b.*\b(this (site|website|dashboard|page|app|portal|tool)|pranav pipeline|the dashboard|the website)\b|\bwhat does this (site|website|dashboard|do)\b/.test(low)) return R('This is the <b>Pranav Pipeline dashboard</b> — a live view of every deal (student) in the Bitrix24 pipeline: stages, universities, programs, semester payments and follow-ups. You can filter, open reports, edit deals (with a comment) and ask me.', 'happy', ['How many deals?', 'Show all reports', 'What can you do?']);
    if ((/\b(data|dashboard|site|website)\b.*\b(old|fresh|stale|updated|refreshed|latest|sync|synced|current|live)\b|\b(last|when)\b.*\b(refresh|refreshed|sync|synced)\b|\bhow old\b.*\bdata\b/.test(low)) && !/\bwho\b|\bstudent\b|\bdeal #?\d|status of\b/.test(low)) { const u = document.getElementById('updated'); return R('The data was loaded: <b>' + E(u ? u.textContent.replace(/^Updated\s*/i, '') : 'a little while ago') + '</b>.<div class="mbs">To get the newest data: account menu (your name, top right) → <b>Refresh data</b>. Your own saves show up straight away.</div>'); }
    if (/\b(how|where)\b.*\b(refresh|reload)\b|\brefresh data\b|\bupdate the data\b/.test(low)) return R('Click your <b>name (top right)</b> → <b>Refresh data</b>. It pulls the latest from Bitrix24. Your own saves show up instantly without that. 🔄');
    if (/\b(log ?out|sign ?out|logoff|log off)\b/.test(low)) return R('Click your <b>name (top right)</b> → <b>Log out</b>. 👋');
    if (/\b(change|reset|forgot|new|update)\b.*\bpassword\b|\bpassword\b.*\b(change|reset|forgot)\b/.test(low)) return R('Click your <b>name (top right)</b> → <b>Change password</b> (at least 8 characters). 🔑 If you forgot it completely, ask the admin to reset it.');
    if (/\b(export|download|csv|excel|spreadsheet|xls)\b/.test(low) && !/\bhow many|count\b/.test(low)) return R('Open any report and press <b>Download Excel</b> (every number links to the deals behind it), or open <b>All deals</b> and press <b>Download CSV</b>. 📥 Filters you set are respected.', 'happy', ['Show all reports']);
    if (/\b(how|where)\b.*\b(search|find)\b|\bctrl ?k\b|\bshortcut\b|\bsearch (bar|box)\b/.test(low)) return R('Use the <b>search bar at the top</b> (or press <b>Ctrl + K</b>) and type a student’s name, phone, email or university. 🔍');
    if (/\bhow\b.*\b(edit|update|change|modify)\b.*\b(deal|lead|student|status|record)\b|\bhow (do|can|to)\b.*\bedit\b/.test(low)) return R('Two ways:<ul class="mbl2"><li><b>With me:</b> say “make sem 1 status paid for Rahul”. I’ll ask for the follow-up date, confirm, save and add a comment.</li><li><b>By hand:</b> open <b>All deals</b>, click the student, change the fields, pick the <b>Next follow-up</b>, add a comment and your name, then Save.</li></ul>');
    if (/\b(dark|light)\s*(mode|theme)\b|\btheme\b/.test(low)) return R('The dashboard follows your <b>device’s dark/light setting</b>. 🌗 Change it in your phone/computer settings.');
    if (/\b(delete|remove|erase)\b.*\b(deal|lead|student|record)\b/.test(low)) return R('I can’t delete deals. 🙊 I only <b>edit</b> them. Deleting must be done in Bitrix24 by someone with permission.', 'cry');
    if (/\b(create|add|new|make)\s+(a\s+|the\s+|one\s+)?(new\s+)?(deal|lead|student|record|entry)\b/.test(low) && !/\breport|latest|last|newest|created|how many|count/.test(low)) return R('I can’t create new deals — I only edit existing ones. 🙈 New deals come from Bitrix24.', 'cry');
    if (/\b(send|write|draft)\b.*\b(email|mail|whatsapp|sms|message)\b|\b(call|dial|ring)\b.*\b(student|him|her|them|phone|number)\b/.test(low)) return R('I’m a bot, not a post office! I can’t send messages or make calls. But I can tell you a student’s phone or email.');
    if (/\b(undo|revert|rollback|roll back)\b/.test(low)) return R('I can’t undo a save. 🙈 But you can just tell me to change it back (for example “make sem 1 status not paid for Rahul”). Every change is recorded in the <b>Activity log</b> report.');
    if (/\b(what|whats|what s|meaning|mean|define|explain)\b.*\b(overdue|stale)\b/.test(low) && !/\bhow many\b|percent|%|count/.test(low)) return R('<b>Follow-up overdue</b> = an active student who has comments, but the latest comment is <b>' + (typeof STALE_DAYS !== 'undefined' ? STALE_DAYS : 15) + '+ days old</b> (or has no date). Closed and alumni deals are not counted.', 'happy', ['How many overdue?']);
    if (/\b(what|whats|what s|meaning|mean|define|explain)\b.*\bnot contacted\b/.test(low)) return R('<b>Not contacted</b> = an active student whose <b>Comment History is empty</b> — nobody has commented on that deal yet.', 'happy', ['How many not contacted?']);
    if (/\b(what|whats|what s|meaning|mean|define|explain)\b.*\balumni\b/.test(low)) return R('<b>Alumni</b> = students who finished their programme (their stage is grouped as “Completed”).');
    if (/\b(what|whats|what s|meaning|mean|define|explain)\b.*\b(re ?reg\w*|re registration)\b/.test(low)) return R('<b>Re-registration</b> = the student paid for the next semester on time. It’s <b>Done</b> when today falls between the Last Payment Date and the Next Payment Date, and <b>Pending</b> when today is past the Next Payment Date. See the <b>Re Reg Percentage</b> report.', 'happy', ['Open re reg percentage report']);
    if (/\b(what|meaning|mean|define|explain)\b/.test(low) && /\b(active|closed)\b/.test(low) && !/\b(how many|count|list|percent|percentage|%|share)\b/.test(low) && /\b(mean|meaning|define|explain|definition|what is|what are|whats)\b/.test(low) && low.split(' ').length <= 8) return R('<b>Closed</b> deals are in a finished stage (completed, dropped, refund, lost…). Everything else is <b>Active</b>.');
    if (/\b(what|which)\b.*\b(stages?)\b.*\b(are there|exist|available|do we have|have)\b|\blist (of )?(all )?stages\b/.test(low)) { const g = groupBy(allRows, 'stage'); return R('Stages in use:' + list_(g.map(x => E(x.k) + ' <span class="mbn">' + fmtN(x.n) + '</span>'))); }
    if (/\b(thank you so much|thanks a lot|many thanks)\b/.test(low)) return R('You’re welcome! 🍌🍌');
    if (/\b(i am|im|i m)\b.*\b(bored|tired|sad|angry|happy|excited)\b/.test(low) && low.split(' ').length <= 5) return R('Aww! Here, have a virtual banana 🍌. Then let’s clear some follow-ups, shall we?', 'happy', ['Overdue follow-ups']);
    if (/\b(test|testing|check|ping)\b\W*$/.test(low) && low.split(' ').length <= 2) return R('I’m awake and swinging! Ask me something.');
    return null;
  }

  /* ================= step-by-step guides ================= */
  const ol_ = a => '<ol class="mbl2">' + a.map(x => '<li>' + x + '</li>').join('') + '</ol>';
  const tips_ = a => '<div class="mbs" style="margin-top:6px"><b>Good to know</b><br>' + a.map(x => '• ' + x).join('<br>') + '</div>';
  const GUIDES = {
    edit: {t: 'How to update or edit a lead (deal)', h: () =>
      '<b>Way 1: ask me (fastest)</b>' + ol_([
        'Tell me <b>who</b> and <b>what</b>: “make sem 1 status paid for Rahul Sharma” or “change stage to Documents for deal 103”. Spelling mistakes are fine.',
        'If several students match, <b>tap the right one</b>.',
        'I ask for the <b>next follow-up date</b> (every save needs one). Say “tomorrow”, “20 Oct”, “in 3 days” or “next monday 4pm”.',
        'I repeat what I understood: the old → new value, the follow-up date and the comment I will write. <b>Check it carefully.</b>',
        'Say <b>yes</b> to save, or <b>no</b> to cancel. To fix something, just tell me (“make it sem 2”, “follow up on 25 Oct”).',
        'I save to Bitrix24 and add a comment like “Pranav (bot): &lt;your name&gt; asked me to update this deal…”.'
      ]) +
      '<b>Way 2: by hand</b>' + ol_([
        'Find the student: type in the <b>search bar</b> at the top (or press <b>Ctrl + K</b>), or open <b>All deals</b>. Click the student to open the <b>edit form</b>.',
        'Change what you need. Changed fields get highlighted.',
        'Pick a new <b>Next Follow-up</b> date. It is required.',
        'Type a <b>comment</b> (what happened or what will happen). It is required.',
        'Check <b>Comment by (your name)</b>. It is remembered for next time.',
        'Press <b>Save to Bitrix24</b>. If Save is greyed out, the line at the bottom of the form tells you what is missing.'
      ]) +
      tips_(['Fixing only the <b>name, phone or email</b> needs no comment and no follow-up date.', 'If someone changed the same field in Bitrix24 meanwhile, you’ll see a conflict message. Nothing is saved; reopen the deal and redo it.', 'Some fields are read-only (for example Next Payment Date (P) and Comment History).', 'Team logins can only see and edit their own team’s deals.', 'I never edit from a phone number or email. I open the form for you instead.']),
      c: ['Edit a deal now', 'Change a semester status', 'Fix a phone number']},
    sem: {t: 'How to change a semester payment status', h: () =>
      '<b>With me:</b> say “make sem 3 status paid for Priya” (or “sem 2 not paid”). I’ll ask the follow-up date, confirm, save and comment.' +
      '<br><b>By hand:</b>' + ol_(['Open the student (search bar or All deals).', 'In the form, find <b>Semester 1 … Semester 6 Status</b> and choose <b>Paid</b> or <b>Not Paid</b>.', 'Pick a new <b>Next Follow-up</b> date.', 'Write a <b>comment</b> and check your <b>name</b>.', 'Press <b>Save to Bitrix24</b>.']) +
      tips_(['A semester with no value shows “Not Paid” by default. That default gets saved together with your next save.', 'The <b>Current semester</b> field is a different field: it says which semester the student is in.']),
      c: ['Edit a deal now']},
    stage: {t: 'How to change a stage, lead status or documents status', h: () =>
      ol_(['Open the student (search bar or All deals).', 'Choose the new value in the dropdown: <b>Stage</b>, <b>Lead Status</b> or <b>Documents Status</b>.', 'Pick a new <b>Next Follow-up</b> date, write a <b>comment</b>, check your <b>name</b>.', 'Press <b>Save to Bitrix24</b>.']) +
      '<div class="mbs" style="margin-top:6px">With me: “set stage to documents for Rahul”, “change lead status to hot for deal 103”.</div>' +
      tips_(['The stage decides whether a student counts as <b>Active</b>, Completed, Dropped or Refund in the reports.']),
      c: ['Edit a deal now']},
    followup: {t: 'How to set the next follow-up date', h: () =>
      ol_(['Open the student.', 'Pick the date in <b>Next Follow-up</b>. It is required for every normal save.', 'Add a comment about the call and press <b>Save</b>.']) +
      '<div class="mbs" style="margin-top:6px">With me: “follow up with Rahul on 25 Oct”.</div>' + tips_(['A deal shows under <b>Follow-up overdue</b> when it has comments but the latest one is 15+ days old. A new comment fixes that.']),
      c: ['What does overdue mean?']},
    comment: {t: 'How to add a comment', h: () =>
      ol_(['Open the student.', 'Type in <b>Comments</b>. It is saved to Bitrix24 with today’s date and time.', 'Check <b>Comment by (your name)</b> and pick a new <b>Next Follow-up</b> date.', 'Press <b>Save</b>. The comment appears in the Comment History.']) +
      tips_(['A comment without any other change is fine; it still needs the follow-up date.']),
      c: ['Edit a deal now']},
    contact: {t: 'How to fix a name, phone or email', h: () =>
      ol_(['Open the student.', 'Edit <b>Student name</b>, <b>Phone</b> or <b>Email</b>. Separate several phones or emails with a comma.', 'Press <b>Save to Bitrix24</b>.']) +
      tips_(['If you change <b>only</b> these three, no comment, name or follow-up date is needed.', 'I won’t edit these for you. I can open the form though: “take me to Rahul”.']),
      c: ['How to find a student']},
    conflict: {t: 'What to do if Save is greyed out or fails', h: () =>
      '<b>Save is greyed out:</b> read the line at the bottom of the form. It says what is missing: a change, a comment, your name or a new Next Follow-up date.' +
      '<br><b>“Someone changed it meanwhile” (conflict):</b>' + ol_(['Nothing was saved.', 'Close the form and open the deal again to see the latest values.', 'Redo your change and save.']) +
      '<br><b>Session ended:</b> sign in again, then retry. If it still fails, tell the admin what the message said.',
      c: ['How to edit a lead']},
    search: {t: 'How to find a student', h: () =>
      ol_(['Click the <b>search bar</b> at the top, or press <b>Ctrl + K</b>.', 'Type a name, phone, email or university.', 'Click a result to open the deal.']) + '<div class="mbs" style="margin-top:6px">Or ask me: “status of Rahul”, “take me to Rahul”, “phone of 9876…”.</div>',
      c: ['How to find a student']},
    filter: {t: 'How to filter the dashboard', h: () =>
      ol_(['On the Dashboard, Master overview or a university page, use the <b>Filters</b> bar: University, Program, Batch, Mode of payment, Stage, Current semester, Team.', 'Each filter allows <b>several choices</b>. The counters update straight away.', 'Press <b>Clear filters</b> to reset.']) +
      '<div class="mbs" style="margin-top:6px">With me: “filter muj + smu + amity”, “only mba and bba”, “clear filters”.</div>',
      c: ['What filter is on?', 'Clear filters']},
    report: {t: 'How to open or build a report', h: () =>
      '<b>Open one:</b> click <b>Reports</b> in the sidebar and choose it (Re-registration tracker, Documents pending, Reference, Re Reg Percentage, Alumni, Activity log).' +
      '<br><b>Build your own:</b>' + ol_(['Open <b>Custom report builder</b>.', 'Choose what goes <b>down the side</b> and <b>across the top</b> (university, program, batch, stage, semester status…).', 'Optionally narrow it with the filters.', 'Click any number to see those students. Press <b>Download CSV</b> to export.']) +
      '<div class="mbs" style="margin-top:6px">With me: “create a report of university by stage for muj + smu” or “open the alumni report”.</div>',
      c: ['Show all reports', 'Create report university by stage']},
    csv: {t: 'How to download data as CSV (Excel)', h: () =>
      ol_(['Open <b>All deals</b> or any report. Apply the filters you want first.', 'In any report press <b>Download Excel</b>: every number in the file is a link that jumps to a sheet with the deals behind it. (On <b>All deals</b>, press <b>Download CSV</b>.)', 'Open the file in Excel or Google Sheets.']), c: ['Show all reports']},
    activity: {t: 'How to see who changed what', h: () =>
      ol_(['Click your <b>name (top right)</b> → <b>Activity log</b> (team logins see <b>Your activity</b>).', 'Filter by person, date or search text.', 'Each line shows the deal, each field’s old → new value, the comment and who made it.']), c: ['Open the activity log']},
    refresh: {t: 'How to refresh the data', h: () => ol_(['Click your <b>name (top right)</b>.', 'Press <b>Refresh data</b>. It pulls the latest from Bitrix24.']) + tips_(['Your own saves show up instantly without refreshing.', 'The top bar shows how old the data is.']), c: ['How old is the data?']},
    password: {t: 'How to change your password or profile', h: () => ol_(['Click your <b>name (top right)</b>.', '<b>Change password</b>: enter the old one and a new one (at least 8 characters).', '<b>Your profile</b>: update your name, phone or colour.']) + tips_(['Forgot it completely? Ask the admin to reset it.']), c: []},
    signout: {t: 'How to log out', h: () => ol_(['Click your <b>name (top right)</b>.', 'Press <b>Log out</b>, then confirm.']), c: []},
    overdue: {t: 'How to work through follow-up overdue', h: () =>
      ol_(['Click <b>Follow-up overdue</b> in the sidebar (or ask me “overdue deals”).', 'Open a student, call them, then add a <b>comment</b> and a new <b>Next Follow-up</b> date.', 'Save. The deal leaves the overdue list.']) + tips_(['Overdue = active student whose latest comment is 15+ days old.', '<b>Not contacted</b> (also in the sidebar) = active students with no comment at all.']), c: ['Overdue follow-ups']},
    rereg: {t: 'How the Re Reg Percentage works', h: () =>
      ol_(['Only students in an <b>Active</b> stage are looked at.', 'No <b>Last Payment Date</b> → not counted at all.', 'Today between Last and Next Payment Date → <b>Done</b>.', 'Everything else (no Next Payment Date, or past it) → <b>Pending</b> (delayed).', '<b>Re-reg % = Done ÷ (Done + Pending).</b>']), c: ['Open re reg percentage report']},
    bot: {t: 'How to use me (Pranav)', h: () => HELP, c: ['Edit a deal now', 'Deals created this month']}
  };
  const GUIDE_MENU = [['edit', 'Edit a lead'], ['sem', 'Semester payment status'], ['stage', 'Stage / lead / documents status'], ['followup', 'Follow-up date'], ['comment', 'Add a comment'], ['contact', 'Fix name / phone / email'], ['search', 'Find a student'], ['filter', 'Filters'], ['report', 'Reports'], ['csv', 'Download CSV'], ['activity', 'Activity log'], ['overdue', 'Overdue follow-ups'], ['refresh', 'Refresh data'], ['password', 'Password / profile']];
  function howTopic(low) {
    const ed = /\b(edit|update|change|modify|fix|correct|set|mark|alter|add)\b/.test(low);
    if (/\b(conflict|someone else (changed|edited)|changed meanwhile|cannot save|can.?t save|couldn.?t save|save (button )?(is )?(disabled|greyed|grayed|not working)|not saved|save fails?|failed to save)\b/.test(low)) return 'conflict';
    if (/\b(phone|mobile|email|mail|student name|contact)\b/.test(low) && ed) return 'contact';
    if (/\b(sem|semester|installment|fee status|payment status)\b/.test(low) && /\b(paid|status|payment|edit|update|change|mark|set)\b/.test(low) && !/\b(report|filter)\b/.test(low)) return 'sem';
    if (/\bfollow ?-?ups?\b/.test(low) && /\b(overdue|pending|due|list|work through|clear)\b/.test(low)) return 'overdue';
    if (/\bfollow ?-?ups?\b/.test(low)) return 'followup';
    if (/\b(comment|note|remark)\b/.test(low)) return 'comment';
    if (/\b(stage|lead status|documents? status|docs status)\b/.test(low) && ed) return 'stage';
    if (/\bre ?reg\w*|re registration\b/.test(low)) return 'rereg';
    if (/\b(overdue|not contacted)\b/.test(low)) return 'overdue';
    if (/\b(search|find|look ?up|locate)\b/.test(low)) return 'search';
    if (/\bfilters?\b/.test(low)) return 'filter';
    if (/\b(csv|export|excel|download)\b/.test(low)) return 'csv';
    if (/\b(report|reports|pivot|custom table)\b/.test(low)) return 'report';
    if (/\b(activity|audit|who changed|change log)\b/.test(low)) return 'activity';
    if (/\b(refresh|reload|latest data|update the data)\b/.test(low)) return 'refresh';
    if (/\b(password|profile)\b/.test(low)) return 'password';
    if (/\b(log ?out|sign ?out)\b/.test(low)) return 'signout';
    if (/\b(you|bot|monkey)\b/.test(low) && /\b(use|work|works|talk|ask|chat)\b/.test(low)) return 'bot';
    if (/\b(edit|update|change|modify|alter)\b/.test(low) && /\b(lead|deal|student|record|details|data|information|info|entry)\b/.test(low)) return 'edit';
    if (ed && /\b(a|the|an|one)\b/.test(low) && /\b(lead|deal)\b/.test(low)) return 'edit';
    return null;
  }
  function howReply(raw) {
    const low = norm(raw);
    const howIntent = /\b(how (do|can|should|to|would|will)|how i|steps?|guide|guidance|process|procedure|instructions?|tutorial|walk me through|teach me|explain how|what should i do|what do i do|where (do|can|should) i|way to|help me (to |with )?(edit|update|change|find|filter|download|add)|what is the process|how does one)\b/.test(low) || /^(how to|how)\b/.test(low) || /\b(can you (guide|teach|show) me)\b/.test(low);
    const stuck = /\b(conflict|cannot save|can.?t save|couldn.?t save|save (button )?(is )?(disabled|greyed|grayed|not working)|not saved|save fails?|failed to save)\b/.test(low);
    if (!howIntent && !stuck) return null;
    if (/\bhow (many|much|old|long|far|often|is|are|was|were|big|come)\b/.test(low)) return null;
    const id = howTopic(low);
    if (!id) {
      if (/^(how|guide|guidance|process|steps|instructions|tutorial|teach me|help me|what should i do)\b/.test(low) && low.split(' ').length <= 6) return {h: 'Happy to guide you! What do you want to learn?<div class="mbact">' + GUIDE_MENU.map(g => '<button type="button" data-q="How to ' + E(g[1].toLowerCase()) + '">' + E(g[1]) + '</button>').join('') + '</div>', mood: 'happy', chips: ['How to edit a lead', 'How to use you']};
      return null;
    }
    const gd = GUIDES[id];
    return {h: '📘 <b>' + E(gd.t) + '</b><div style="margin-top:6px">' + gd.h() + '</div>', mood: 'happy', chips: gd.c.length ? gd.c : ['How to edit a lead', 'What can you do?']};
  }

  /* ---- data quality & percentages ---- */
  function statReply(U, raw) {
    const low = norm(raw), s = U.s;
    const F = valueFilters(U);
    const base = allRows.filter(r => F.every(f => f.test(r)));
    const ft = F.length ? filtText(F) : '';
    const line = (arr, what) => { const n = arr.length; return {h: '<b>' + numLink(arr, 'Deals that ' + what.replace(/<[^>]+>/g, '')) + '</b> deal' + (n === 1 ? '' : 's') + ft + ' ' + what + '.', mood: 'happy'}; };
    const miss = /\b(no|without|missing|empty|blank|lack|lacking)\b/.test(low) || /\bdont have\b|\bdo not have\b/.test(low);
    if (miss && /\b(phone|mobile|number|contact)\b/.test(low)) return line(base.filter(r => !String(r._PHONE || '').replace(/\D/g, '')), 'have <b>no phone number</b>');
    if (miss && /\bemail\b|\bmail\b/.test(low)) return line(base.filter(r => !String(r._EMAIL || '').trim()), 'have <b>no email</b>');
    if (miss && /\bfollow\b/.test(s)) return line(base.filter(r => !fuOf(r)), 'have <b>no follow-up date</b>');
    if (miss && /\buniversit|\buni\b/.test(low + ' ' + s)) return line(base.filter(r => uniOf(r) === UNASSIGNED), 'have <b>no university</b> set');
    if (miss && /\bprogram|\bcourse\b/.test(low + ' ' + s)) return line(base.filter(r => !val(r, 'program')), 'have <b>no program</b> set');
    if (miss && /\bbatch\b/.test(s)) return line(base.filter(r => !val(r, 'batch')), 'have <b>no batch</b> set');
    if (miss && /\bcomment|\bnote\b|\bremark\b/.test(s)) return line(base.filter(r => !lastCm(r)), 'have <b>no comments</b>');
    if (/\bduplicat\w*\b|\brepeated\b|\bsame (phone|number)\b/.test(low) && /\b(phone|number|mobile|email|deals?|students?|names?)\b/.test(low)) {
      const m = {}; base.forEach(r => { const k = String(r._PHONE || '').replace(/\D/g, '').slice(-10); if (k.length >= 8) (m[k] = m[k] || []).push(r); });
      const d = Object.keys(m).filter(k => m[k].length > 1);
      const n = d.reduce((a, k) => a + m[k].length, 0);
      if (!d.length) return {h: 'No duplicate phone numbers' + ft + '. ✨', mood: 'happy'};
      return {h: '<b>' + d.length + '</b> phone number' + (d.length === 1 ? ' is' : 's are') + ' shared by <b>' + n + '</b> deals' + ft + '. First few:' + list_(d.slice(0, 5).map(k => m[k].slice(0, 3).map(r => dealLink(r)).join(' · '))), mood: 'happy'};
    }
    if (/\b(percent|percentage|%|share|ratio|proportion)\b/.test(low) && !/\breport\b/.test(low)) {
      if (!F.length) return {h: 'Tell me which group to measure, for example “what percent of deals are active”, “percentage of sem 1 paid” or “what percent are in Amity”.', mood: 'idle'};
      const n = base.length, tot = allRows.length;
      return {h: F.map(f => E(f.label)).join(' + ') + ': <b>' + numLink(base, F.map(f => f.label).join(' + ')) + '</b> of <b>' + fmtN(tot) + '</b> deals = <b>' + (tot ? (100 * n / tot).toFixed(1) : '0') + '%</b>.', mood: 'happy'};
    }
    if (/\baverage\b|\bavg\b|\bper (university|program|batch|team)\b/.test(low) && /\b(deals?|students?)\b/.test(low + ' ' + s)) {
      const role = /\bprogram/.test(low) ? 'program' : /\bbatch/.test(low) ? 'batch' : /\bteam/.test(low) ? 'team' : 'uni';
      const g = groupBy(base, role).filter(x => x.k !== '(empty)' && x.k !== UNASSIGNED);
      if (!g.length) return null;
      const n = g.reduce((a, x) => a + x.n, 0);
      return {h: 'On average <b>' + (n / g.length).toFixed(1) + '</b> deals per ' + NOUN_LABEL[role][0] + ' (' + fmtN(n) + ' deals across ' + g.length + ' ' + NOUN_LABEL[role][1] + ').', mood: 'happy'};
    }
    return null;
  }

  async function extras(raw, U) {
    const low = norm(raw);
    { const hw = howReply(raw); if (hw) return hw; }
    // never steal a clear edit instruction
    if (U.hasSetVerb && /\b(set|make|change|update|mark|edit|modify|put|turn|convert|chnge|updte|chage)\b/.test(low) && fieldsIn(U.s, U.s).length && !/\bfilters?\b|\breport\b/.test(low)) return null;
    let r;
    if ((r = await workReply(U, raw))) return r;
    if ((r = reportReply(U, raw))) return r;
    if ((r = filterReply(U, raw))) return r;
    if ((r = kbReply(U, raw))) return r;
    if ((r = latestReply(U, raw))) return r;
    if ((r = periodReply(U, raw)) && (/\b(how many|count|number|total|list|show|which|who|deals?|students?|leads?|due|created|added|new|came|joined|follow)\b/.test(low))) return r;
    if ((r = statReply(U, raw))) return r;
    return null;
  }

  /* ---------- main brain ---------- */
  const YES = /^(y|yes|yep|yeah|yup|ya|yea|ok|okay|sure|confirm|confirmed|do it|go ahead|save|save it|correct|right|haan|ha|done|proceed|approve|accept|yes please|please do|👍|✅)\W*$/;
  const NO = /^(n|no|nope|nah|cancel|stop|dont|do not|abort|wrong|nahi|no thanks|never mind|nevermind|leave it|discard|✖|❌)\W*$/;
  async function brain(text) {
    let raw = text.trim();
    if (/\bkitn[eai]\b/i.test(raw)) raw = raw.replace(/\b(hain|hai|h)\b/gi, ' ').replace(/\b([a-z0-9&+]+)\s+(me|mein|main)\b/gi, 'in $1').replace(/\b(ka|ke|ki)\b/gi, ' ');
    raw = raw.replace(/\b(kitn[eai]|ginti|gintee)\b/gi, 'how many').replace(/\bhow\s+much\b(?=\s+(deals?|leads?|students?|universit|programs?|batch))/gi, 'how many').replace(/\b(tally|headcount|head count)\b/gi, 'count');
    if (raw.indexOf('@') < 0) raw = raw.replace(/([a-z0-9])\s+\+\s+(?=[a-z0-9])/gi, '$1 ').replace(/([a-z0-9])\+(?=[a-z])/gi, '$1 ');
    const low = norm(raw);
    // "take me to <student>" -> open that deal with its edit form
    {
      const nm = /^(?:please |pls |can you |could you )?(?:take me to|take me|open|go to|goto|navigate to|bring up|pull up|jump to|view|show me the deal of|show me deal of)\s+(?:the\s+)?(?:deal\s+(?:of\s+|for\s+)?|student\s+|edit\s+(?:form\s+)?(?:of\s+|for\s+)?)?(.+)$/i.exec(raw);
      if (nm && allRows.length && !/\b(report|reports|filter|filters|dashboard|sheet|universit\w*|programs?|batch\w*|overdue|alumni|activity|log|home|master|deals|students)\b/i.test(nm[1])) {
        const Un = understand(nm[1]);
        if (!valueGroups(Un.T, Un.C, {}).used[0] && !Object.keys(valueGroups(Un.T, Un.C, {}).G).length) {
          const fn = findDeals(Un.T, Un.C);
          const openIt = r => { S.edit = null; S.pending = null; S.deal = r; S.via = fn.how || 'name'; try { openEdit(r); } catch (e) {} setTimeout(() => { try { openPanel(false); } catch (e) {} }, 250); return {h: '📂 Opened <b>' + E(nameOf(r)) + '</b> (#' + E(r.ID) + ') with its edit form. Change what you need, add the follow-up date, a comment and your name, then Save. I’ll be here if you need me.', mood: 'happy'}; };
          if (fn.list.length === 1) return openIt(fn.list[0].r);
          if (fn.list.length > 1) { S.edit = null; S.viaNext = fn.how; return showWho(fn.list, openIt); }
        }
      }
    }
    // confirmation step
    if (S.edit && S.edit.step === 'confirm') {
      if (YES.test(low)) return executeEdit();
      if (NO.test(low)) { S.edit = null; return {h: 'No problem, I cancelled it. Nothing was changed. 👍', mood: 'idle'}; }
      const d = nlDate(raw), U0 = understand(raw), p0 = parseEdit(U0, S.edit.deal);
      if (p0.items.length || (d && /\bfollow\b|\bon\b|\bby\b|tomorrow|next|in \d/.test(U0.s))) {
        const e = S.edit, fuCol = colByRole('fuupd');
        p0.items.forEach(it => { if (fuCol && it.col.key === fuCol.key) return; let old = String(e.deal[it.col.key] || ''); if (!old && /^semstat/.test(it.col.role || '')) old = 'Not Paid'; const ix = e.items.findIndex(x => x.col.key === it.col.key); const row = {col: it.col, v: it.v, old: old}; if (ix >= 0) e.items[ix] = row; else e.items.push(row); });
        if (d && (!p0.items.length || /\bfollow\b/.test(U0.s))) e.fu = d;
        return toConfirm();
      }
      { const Ux = understand(raw), fx = findDeals(Ux.T, Ux.C); if (fx.list.length || Ux.question || /\b(how many|count|filter|report|show|open|take me|who|when|what)\b/.test(low)) { S.edit = null; }
        else return {h: 'I’m waiting for a <b>yes</b> or a <b>no</b> on the change above. 🙂', mood: 'idle', chips: ['Yes', 'No']}; }
    }
    if (S.edit && S.edit.step === 'fu') {
      if (NO.test(low) || /^cancel\b/.test(low)) { S.edit = null; return {h: 'Okay, cancelled. Nothing was changed. 👍', mood: 'idle'}; }
      const d = nlDate(raw);
      if (d) { S.edit.fu = d; return toConfirm(); }
      { const Ux = understand(raw), fx = findDeals(Ux.T, Ux.C); if (fx.list.length || Ux.question || /\b(how many|count|filter|report|show|open|take me|who|when|what|edit|set|make|change)\b/.test(low)) { S.edit = null; }
        else return {h: 'I couldn’t read that as a date. 🙈 Try “tomorrow”, “20 Oct”, “in 3 days” or “next monday 4pm”.', mood: 'conf', chips: ['Tomorrow', 'In 3 days', 'Next week', 'Cancel']}; }
    }
    if (S.pending && S.pending.t === 'which') {
      if (NO.test(low) || /^cancel\b/.test(low)) { S.pending = null; return {h: 'Okay, forget it. 👍', mood: 'idle'}; }
      const U1 = understand(raw), f = findDeals(U1.T, U1.C);
      let pick = null;
      const ownIntent = attrsOf(U1.s).length || U1.hasSetVerb || /\bhow many\b|\bhow\b.*\bmany\b/.test(U1.s);
      if (ownIntent) { S.pending = null; } else {
      const num = /^\d{1,2}$/.test(low) ? +low : 0;
      if (num && S.pending.list[num - 1]) pick = S.pending.list[num - 1];
      else if (f.list.length) { const inList = f.list.filter(x => S.pending.list.some(y => y.ID === x.r.ID)); if (inList.length === 1) pick = inList[0].r; else if (f.list.length === 1) pick = f.list[0].r; }
      if (pick) { const then = S.pending.then; S.pending = null; S.deal = pick; S.via = (f && f.how) || S.viaNext || 'name'; return then(pick); }
      S.pending = null;
      }
    }
    if (S.pending && S.pending.t === 'sem') {
      const m = /\b(\d)\b/.exec(low) || (() => { const w = /\b(one|two|three|four|five|six|first|second|third|fourth|fifth|sixth)\b/.exec(low); return w ? [0, NUMW[w[1]]] : null; })();
      if (m) { const p = S.pending; S.pending = null; return p.then(+m[1]); }
      if (NO.test(low)) { S.pending = null; return {h: 'Okay, cancelled. 👍', mood: 'idle'}; }
      S.pending = null;
    }
    if (NO.test(low) && !S.edit && !S.pending) return {h: 'Okay 👍 nothing pending on my side.', mood: 'idle'};
    { const g1 = low.split(' ')[0] || ''; const nw = low.split(' ').length;
      if (nw <= 2 && g1.length >= 3) { if (near(g1, 'hello') || near(g1, 'namaste') || near(g1, 'hiii')) return {h: 'Hello' + (WHO() ? ' <b>' + E(WHO()) + '</b>' : '') + '! Ask me about any student or a count, or tell me which lead to open for editing.', mood: 'happy', chips: baseChips()}; if (near(g1, 'thanks') || near(g1, 'thankyou')) return {h: 'Anytime! 🍌', mood: 'happy'}; } }
    // social
    for (const sc of SOCIAL) {
      if (sc[0].test(low)) {
        if (sc[1] === 'hi') return {h: 'Hello' + (WHO() ? ' <b>' + E(WHO()) + '</b>' : '') + '! Ask me about any student or a count, or tell me which lead to open for editing.', mood: 'happy', chips: baseChips()};
        if (sc[1] === 'thanks') return {h: 'Anytime! 🍌 A banana for you too.', mood: 'happy'};
        if (sc[1] === 'bye') return {h: 'Bye bye! 🙊 I’ll be hanging around here.', mood: 'happy'};
        if (sc[1] === 'who') return {h: 'I’m <b>Pranav</b> — the bot built to look after this dashboard. I answer from your live data, and when you want to edit a lead I open it for you.', mood: 'happy'};
        if (sc[1] === 'help') return {h: HELP, mood: 'happy', chips: baseChips()};
        if (sc[1] === 'ok') return {h: '👍', mood: 'idle'};
        if (sc[1] === 'howru') return {h: 'Swinging great! 🍌 What shall we look up?', mood: 'happy'};
        if (sc[1] === 'lol') return {h: '🙊', mood: 'happy'};
      }
    }
    if (!allRows.length) return {h: 'I don’t have any deals loaded yet. Open the account menu → “Refresh data”, then ask me again. 🍌', mood: 'conf'};
    const U = understand(raw);
    const f = findDeals(U.T, U.C);
    const pron = /\b(his|her|him|he|she|its|this|that|same|it)\b/.test(U.T.join(' '));
    const ex = await extras(raw, U); if (ex) return ex;
    // ---- edit ----
    const editish = U.hasSetVerb && !/^\W*(who|when|what|how many|which)\b/.test(U.s);
    if (editish || (/\bto\b|\bas\b/.test(U.s) && PAY_WORDS.test(U.s) && /\bsem\b/.test(U.s) && !U.question)) {
      if ((f.how === 'phone' || f.how === 'email') && f.list.length) return guideEdit(f.list.map(x => x.r), U, f.how);
      const go = deal => guideEdit([deal], U, f.how);
      if (f.list.length === 1) { S.deal = f.list[0].r; S.via = f.how; return go(f.list[0].r); }
      if (f.list.length > 1) { S.viaNext = f.how; return showWho(f.list, go); }
      if (curDeal() && (pron || !f.list.length)) {
        { const nm_ = /\b(?:for|of|student|named|called)\s+([a-z][a-z.]{2,})/.exec(norm(raw)); if (nm_ && !pron && !LEXV[nm_[1]] && !STOP.has(nm_[1]) && !/^(deal|this|that|current|him|her|them|same|sem|semester|status|stage|lead|leads)$/.test(nm_[1]) && !valueDictHas(nm_[1])) return {h: 'I couldn’t find a student called <b>“' + E(nm_[1]) + '”</b> 🙈 Check the spelling, or give me the full name or deal number.', mood: 'conf'}; }
        return go(curDeal());
      }
      setTimeout(() => { try { openPanel(false); const g = document.getElementById('gsearch'); if (g) g.focus(); } catch (e) {} }, 350);
      return {h: 'Oh! Editing is <b>your</b> job, boss, I only swing around the data! I don’t know which lead you mean, so I opened the <b>search box</b>. Pick the lead there and its edit form opens. 🍌', mood: 'happy'};
    }
    // ---- counting ----
    const isCount = (/\b(least|fewest|lowest|smallest|most|highest|biggest|largest)\b/.test(low) && /\b(universit\w*|program\w*|batch\w*|team\w*|payment mode|deals?)\b/.test(low) && !/\b(worked|filter)\b/.test(low)) || /\b(how many|count|total|number of|no of)\b/.test(U.s.replace(/\bhow\b \bmany\b/, 'how many')) || (/\bhow\b/.test(U.s) && /\bmany\b/.test(U.s)) || /\b(each|most|least|fewest|lowest|smallest)\b/.test(U.s) && /\b(university|program|batch|team|status|mode|deal)\b/.test(U.s) || /^(list|show|display)?\s*(all )?(universit|program|batch|team)/.test(U.s);
    const cntKw = /\b(how many|count|number of|no of|total)\b/.test(raw.toLowerCase().replace(/\bno\.\s*of\b/, 'no of'));
    if (isCount && f.list.length && cntKw && !/\b(least|fewest|lowest|smallest|most|highest|biggest|largest|each)\b/.test(low)) {
      if (parseFilters(U.T, U.C, U.skip).length) return doCount(U);
      const L = f.list.map(x => x.r), n = L.length;
      return {h: 'I found <b>' + numLink(L, 'Matching “' + raw.slice(0, 40) + '”') + '</b> deal' + (n === 1 ? '' : 's') + ' matching that.' + (n <= 6 ? list_(L.map(r => dealLink(r))) : '<div class="mbs">Too many to list; be more specific (full name, phone or deal number).</div>'), mood: 'happy'};
    }
    if (isCount && !f.list.length) { return doCount(U); }
    if (/^(overdue|stale|not contacted)\b/.test(U.s) || /\b(overdue|not (yet )?(contacted|worked|touched|called))\b/.test(U.s) && !f.list.length) return doCount(Object.assign(U, {s: 'how many ' + U.s}));
    // ---- a student ----
    const attrs = attrsOf(U.s);
    if (!f.list.length && !attrs.length && /\b(deals?|students?)\b/.test(U.s) && !/\b(set|filter|report)\b/.test(U.s) && parseFilters(U.T, U.C, U.skip).length) return doCount(Object.assign(U, {s: 'how many ' + U.s}));
    if (f.list.length === 1) {
      S.deal = f.list[0].r; S.via = f.how;
      return answerAttr(f.list[0].r, attrs, U.s);
    }
    if (f.list.length > 1) { S.viaNext = f.how; return showWho(f.list, d => answerAttr(d, attrs, U.s)); }
    if (curDeal() && attrs.length && (pron || U.T.length <= 6)) return answerAttr(curDeal(), attrs, U.s);
    if (attrs.length && /\b(who|when|status|phone|email|follow|created)\b/.test(U.s) && DOMAIN_RX.test(U.s) && (U.question || attrs.length)) {
      if (/\b(student|deal)\b/.test(U.s) || /\b(status|created|phone|email|worked)\b/.test(U.s)) {
        S.pending = {t: 'which', then: d => answerAttr(d, attrs, U.s), list: []};
        return {h: 'Sure! Which student? Tell me the name, phone number or deal number.', mood: 'idle', chips: ['Cancel']};
      }
    }
    // names not found but looks like a person lookup
    if (/\b(status|created|phone|email|who|worked|detail|follow)\b/.test(U.s) && U.T.some(t => !STOP.has(t) && !LEXV[t] && t.length > 2 && !/^\d+$/.test(t))) {
      const w = U.T.filter(t => !STOP.has(t) && !LEXV[t] && t.length > 2 && !/^\d+$/.test(t) && LEXV[fixTok(t)] === undefined).slice(0, 3).join(' ');
      if (w) return {h: 'Hmm, I searched but couldn’t find a student matching <b>“' + E(w) + '”</b> among the deals you can see. 🙈 Try the full name, phone number or deal number.', mood: 'conf'};
    }
    if (!attrs.length && !U.hasSetVerb && parseFilters(U.T, U.C, U.skip).length) return doCount(Object.assign(U, {s: 'how many ' + U.s}));
    // domain but unanswerable vs out of topic
    const words = U.C.join(' ');
    if (DOMAIN_RX.test(words) || U.T.some(t => dataTokens().has(t) && t.length > 3 && !STOP.has(t))) return notTrained();
    return outOfTopic();
  }

  async function send(text) {
    text = String(text || '').trim(); if (!text || S.busy) return;
    S.speakNext = !!S.voiceDraft; S.voiceDraft = false; try { if (window.PPSound) window.PPSound.stop(); } catch (e) {}
    addMe(text); input.value = ''; S.asked = text; setChips([]); track('bot', text);
    await think(() => brain(text), Math.min(900, 380 + text.length * 6));
  }

  /* ================= watcher: knows what is happening on the page ================= */
  const EV = [], BOT_IDS = {};
  const evAdd = t => { EV.push({at: Date.now(), t: t}); if (EV.length > 40) EV.shift(); };
  const agoTxt = ms => { const m = Math.round(ms / 60000); return m < 1 ? 'just now' : m < 60 ? m + ' min ago' : Math.round(m / 60) + ' h ago'; };
  const modalOpen = () => { try { return !!editRow && !g('modal').hidden; } catch (e) { return false; } };
  const curDeal = () => (modalOpen() ? editRow : null) || S.deal;
  function pageText() {
    try {
      const r = route;
      if (r.type === 'home') return 'the <b>Dashboard</b> home';
      if (r.type === 'master') return r.sheet ? 'the <b>All deals</b> table' : 'the <b>Master overview</b>';
      if (r.type === 'uni') return 'the <b>' + E(r.name.split(MS).join(' + ')) + '</b> page' + (r.sheet ? ' (deals table)' : '');
      if (r.type === 'reports') return 'the <b>Reports</b> list';
      if (r.type === 'report') { const d = REPORTS.find(x => x.id === r.id); return 'the <b>' + E(d ? d.title : 'report') + '</b> report'; }
    } catch (e) {}
    return 'the dashboard';
  }
  let WS = {hash: location.hash, gf: '', edit: null, fetched: null, n: -1, touched: {}};
  function watchTick() {
    try {
      if (!window.allRows || !allRows.length) return;
      const gf = JSON.stringify(gfNow());
      if (WS.n === -1) { WS = {hash: location.hash, gf: gf, edit: modalOpen() ? editRow : null, fetched: fetchedAt, n: allRows.length, touched: Object.assign({}, TOUCHED)}; return; }
      const notes = [];
      if (location.hash !== WS.hash) { WS.hash = location.hash; evAdd('Went to ' + pageText().replace(/<[^>]+>/g, '')); }
      if (gf !== WS.gf) { WS.gf = gf; const n = gfNow(); evAdd(Object.keys(n).length ? 'Filtered: ' + gfText(n).replace(/<[^>]+>/g, '') : 'Cleared the filters'); }
      const ed = modalOpen() ? editRow : null;
      if ((ed && ed.ID) !== (WS.edit && WS.edit.ID)) { if (ed) evAdd('Opened the edit form of ' + nameOf(ed)); else if (WS.edit) evAdd('Closed the edit form of ' + nameOf(WS.edit)); WS.edit = ed; }
      if (fetchedAt !== WS.fetched) { WS.fetched = fetchedAt; if (WS.n !== allRows.length) evAdd('Data refreshed (' + fmtN(allRows.length) + ' deals)'); else evAdd('Data refreshed'); notes.push('Data was just refreshed from Bitrix24 (' + fmtN(allRows.length) + ' deals).'); WS.n = allRows.length; }
      Object.keys(TOUCHED).forEach(id => { if (TOUCHED[id] !== WS.touched[id]) { WS.touched[id] = TOUCHED[id]; if (!(BOT_IDS[id] && Date.now() - BOT_IDS[id] < 8000)) { const r = allRows.find(x => String(x.ID) === String(id)); evAdd('Saved changes to ' + (r ? nameOf(r) : 'deal #' + id)); notes.push('You just saved changes to <b>' + E(r ? nameOf(r) : '#' + id) + '</b>. 👍'); } } });
      if (notes.length && panel && !panel.hidden && !S.busy) { addBot('👀 ' + notes.join('<br>')); mood('happy'); }
    } catch (e) {}
  }
  setInterval(watchTick, 2500);
  function watchSummary() {
    const act = allRows.filter(r => !isClosed(r)).length, od = allRows.filter(r => isStale(r)).length, nc = allRows.filter(r => isNotContacted(r)).length;
    const n = gfNow(), cd = modalOpen() ? editRow : null;
    let h = '👀 Here’s what I can see right now:<ul class="mbl2"><li>You’re on ' + pageText() + '.</li>';
    h += '<li>' + (Object.keys(n).length ? 'Filters on: ' + gfText(n) + ' (showing ' + fmtN(gfRows.length) + ' of ' + fmtN(allRows.length) + ')' : 'No filters are on (' + fmtN(allRows.length) + ' deals).') + '</li>';
    if (cd) h += '<li>The edit form of <b>' + E(nameOf(cd)) + '</b> is open.</li>';
    else if (S.deal) h += '<li>We were last talking about <b>' + E(nameOf(S.deal)) + '</b>.</li>';
    h += '<li><b>' + fmtN(act) + '</b> active deals · <b>' + fmtN(od) + '</b> follow-ups overdue · <b>' + fmtN(nc) + '</b> not contacted.</li>';
    try { const u = g('updated'); if (u) h += '<li>Data: ' + E(u.textContent.replace(/^Updated\s*/i, '')) + '.</li>'; } catch (e) {}
    h += '</ul>';
    const ev = EV.slice(-6).reverse();
    if (ev.length) h += '<div class="mbs" style="margin-bottom:3px">Recent moves:</div>' + list_(ev.map(x => E(x.t) + ' <span class="mbs">' + agoTxt(Date.now() - x.at) + '</span>'));
    else h += '<div class="mbs">Nothing has happened since I started watching. 😴</div>';
    return h;
  }
  const GREET = [
    'Ahh, <b>{n}</b>, you again?! 🙄 Came to eat my head, or was I sleeping peacefully? 😴',
    'Oh no, he’s back! I was in the middle of a banana nap. 🍌😴 What now?',
    'Hide the bananas, <b>{n}</b> is here! 🙈 What do you want this time?',
    'Mmm? Huh? I wasn’t sleeping! I was… resting my eyes. 😪 Go on, <b>{n}</b>.',
    'I was just counting deals in my sleep… 1,193… 1,194… and you made me lose count! 😤',
    'Poke poke! Who woke me up? Oh, it’s you, <b>{n}</b>. Fine, I’m up.',
    'Reporting for duty (after a short nap). 🫡🍌 What’s the emergency, <b>{n}</b>?',
    'Ah, the human who types too much returns. 🙊 Questions? Complaints? Bananas?',
    'Oo-oo-ah-ah! 🎉 Oh, it’s just you. I thought it was the admin coming to train me. 😭',
    'Back already?! I haven’t even finished my banana. 🍌 Make it quick, <b>{n}</b>.',
    'Did someone say follow-ups? No? Then why did you wake me up? 😴 Okay okay, ask away.',
    'Knock knock… oh wait, you don’t knock, you just poke me. Yes, <b>{n}</b>?'
  ];
  const GREET_LONG = ['Where have you been, <b>{n}</b>?! I got so lonely I started reading the activity log. 🥲', 'It’s been ages! I almost started talking to the progress bars. 🙊 Welcome back, <b>{n}</b>!'];
  const GREET_QUICK = ['Back <i>already</i>?! I haven’t even finished my banana. 🍌 You’re making me dizzy, <b>{n}</b>.', 'Again?! I just sat down! 😤 Fine, what is it?'];
  const GREET_NIGHT = ['It’s late, <b>{n}</b>! Even bots need rest at night. 🌙 But go on, I’ll stay up.'];
  const GREET_MORN = ['Good morning, <b>{n}</b>! ☀️ Is it banana time yet?'];
  let lastGreet = 0, lastClose = 0, gi = -1;
  function ctxNotes() {
    const out = [];
    try {
      let seen = null; try { seen = JSON.parse(localStorage.getItem('mb_seen') || 'null'); } catch (e) {}
      const maxId = allRows.reduce((m, r) => Math.max(m, +r.ID || 0), 0);
      if (seen && seen.max && maxId > seen.max) { const k = allRows.filter(r => (+r.ID || 0) > seen.max).length; if (k) out.push('👀 <b>' + k + '</b> new deal' + (k === 1 ? '' : 's') + ' since you last opened me.'); }
      try { localStorage.setItem('mb_seen', JSON.stringify({max: maxId, at: Date.now()})); } catch (e) {}
      const od = allRows.filter(r => isStale(r)).length;
      if (od) out.push('⏰ <b>' + fmtN(od) + '</b> follow-up' + (od === 1 ? ' is' : 's are') + ' overdue.');
      const n = gfNow(); if (Object.keys(n).length) out.push('🎛 Filters are on: ' + gfText(n) + ' (' + fmtN(gfRows.length) + ' deals).');
      if (route.type !== 'home') out.push('📍 You’re on ' + pageText() + '.');
      if (fetchedAt && Date.now() - fetchedAt > 3600e3) out.push('🕰 The data is ' + agoTxt(Date.now() - fetchedAt).replace(' ago', '') + ' old. Press “Refresh data” if you need it fresh.');
      const last = EV.slice().reverse().find(x => /^Saved|^Opened/.test(x.t)); if (last) out.push('📝 ' + last.t + ' ' + agoTxt(Date.now() - last.at) + '.');
    } catch (e) {}
    return out.slice(0, 2);
  }
  function greetOpen(first) {
    if (Date.now() - lastGreet < 1500) return;
    lastGreet = Date.now();
    const nm = WHO() || 'friend', h = new Date().getHours(), away = lastClose ? Date.now() - lastClose : 0;
    let pool = GREET;
    if (away && away < 25000) pool = GREET_QUICK; else if (away > 40 * 60000) pool = GREET_LONG; else if (h >= 23 || h < 5) pool = GREET_NIGHT; else if (h >= 5 && h < 9 && Math.random() < .5) pool = GREET_MORN;
    let i; do { i = Math.floor(Math.random() * pool.length); } while (pool === GREET && i === gi && pool.length > 1); if (pool === GREET) gi = i;
    let t = pool[i].replace(/\{n\}/g, E(nm));
    const notes = allRows.length ? ctxNotes() : [];
    if (first) t += '<div class="mbs" style="margin-top:6px">I’m <b>Pranav</b>, the bot built for this dashboard. I watch it, answer from its data, and open any lead you want to edit. Typos are fine!</div>';
    if (notes.length) t += '<div class="mbs" style="margin-top:6px">' + notes.join('<br>') + '</div>';
    respond({h: t, mood: 'happy', chips: baseChips()});
  }

  /* ---------- events ---------- */
  function openPanel(o) {
    if (!o && !panel.hidden) lastClose = Date.now();
    panel.hidden = !o; openB.classList.toggle('open', o); openB.setAttribute('aria-expanded', o ? 'true' : 'false'); openB.classList.remove('hasq');
    if (o) {
      greetOpen(!msgs.children.length);
      setTimeout(() => input.focus(), 60);
    }
  }
  openB.onclick = () => { if (!panel.hidden) micAbort(); openPanel(panel.hidden); };
  g('mbClose').onclick = () => { micAbort(); openPanel(false); openB.focus(); };
  g('mbClear').onclick = () => { talked = false; msgs.innerHTML = ''; S.deal = null; S.pending = null; S.edit = null; respond({h: 'Fresh start! 🍌 What shall we look up?', mood: 'happy', chips: baseChips()}); };
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !panel.hidden && !document.querySelector('.modal:not([hidden])')) { openPanel(false); } });

  /* ================= voice to text ================= */
  const SR = window.SpeechRecognition || window.webkitSpeechRecognition, micB = g('mbMic');
  let rec = null, listening = false, micErr = '';
  const PH = input.placeholder;
  function micNote(t, ms) { const e = g('mbSub'); if (!e) return; const was = e.dataset.was || e.textContent; e.dataset.was = was; e.textContent = t; clearTimeout(micNote.t); micNote.t = setTimeout(() => { e.textContent = e.dataset.was; delete e.dataset.was; }, ms || 4500); }
  function micAbort() { if (rec && listening) { micErr = 'x'; try { rec.abort(); } catch (e) {} } }
  if (!SR) { micB.hidden = true; } else {
    micB.onclick = () => {
      if (S.busy) return;
      if (listening) { try { rec.stop(); } catch (e) {} return; }
      micErr = ''; input.value = ''; try { wkStop(); } catch (e) {}
      rec = new SR(); rec.lang = 'en-IN'; rec.interimResults = true; rec.continuous = false; rec.maxAlternatives = 1;
      rec.onstart = () => { try { window.PPSound && window.PPSound.play('blip'); window.PPSound && window.PPSound.stop(); } catch (e) {} listening = true; micB.classList.add('rec'); input.classList.add('lis'); input.placeholder = 'Listening… speak now 🎙️'; micB.title = 'Tap to stop'; };
      rec.onresult = ev => { let f = '', i2 = ''; for (let i = 0; i < ev.results.length; i++) { const r = ev.results[i]; if (r.isFinal) f += r[0].transcript; else i2 += r[0].transcript; } input.value = (f + ' ' + i2).replace(/\s+/g, ' ').trim(); };
      rec.onerror = ev => {
        const e = ev && ev.error; if (e === 'aborted') return; micErr = e || 'err';
        micNote(e === 'not-allowed' || e === 'service-not-allowed' ? 'Mic is blocked. Allow the microphone in the browser address bar 🎙️' : e === 'no-speech' ? 'I did not hear anything. Tap the mic and try again 🙉' : e === 'audio-capture' ? 'No microphone found 🎙️' : e === 'network' ? 'Voice needs an internet connection 🌐' : 'Voice did not work. Please type instead 🙈', 5000);
      };
      rec.onend = () => {
        listening = false; micB.classList.remove('rec'); input.classList.remove('lis'); input.placeholder = PH; micB.title = 'Speak your question';
        const t = input.value.trim(); try { window.PPSound && window.PPSound.play('unblip'); } catch (e) {}
        if (!micErr && t) { S.voiceDraft = true; input.focus(); try { input.setSelectionRange(t.length, t.length); } catch (e) {} } else if (micErr === 'x') input.value = '';
        else if (!micErr) micNote('I did not catch that. Tap the mic and try again 🙉', 3500);
      };
      try { rec.start(); } catch (e) { micNote('Voice did not start. Please try again.', 3500); }
    };
  }

  /* ================= say "Hey Pranav" to wake the bot (opt-in) ================= */
  const WKEY = 'pp_wake', wkItem = g('wakeItem'), wkTxt = g('wakeMode');
  let wkOn = false, wkRec = null, wkRun = false, wkFail = 0, wkLast = 0;
  try { wkOn = localStorage.getItem(WKEY) === '1'; } catch (e) {}
  const wkPaint = () => { if (wkTxt) wkTxt.textContent = 'Say “Hey Pranav”: ' + (wkOn ? 'on 🟢' : 'off'); };
  const GREETW = /^(hey|hay|hi|hii|hello|ok|okay|ay|a|he)$/;
  const lev = (x, y) => { const m = []; for (let i = 0; i <= x.length; i++) { m[i] = [i]; } for (let j = 1; j <= y.length; j++) m[0][j] = j; for (let i = 1; i <= x.length; i++) for (let j = 1; j <= y.length; j++) m[i][j] = Math.min(m[i - 1][j] + 1, m[i][j - 1] + 1, m[i - 1][j - 1] + (x[i - 1] === y[j - 1] ? 0 : 1)); return m[x.length][y.length]; };
  function wkMatch(t) {
    const w = String(t || '').toLowerCase().replace(/[^a-z\s]/g, ' ').split(/\s+/).filter(Boolean);
    for (let i = 0; i < w.length; i++) {
      const x = w[i], near = x.length >= 4 && lev(x, 'pranav') <= 2 && /^[pbf]r/.test(x);
      if (near && (i > 0 && GREETW.test(w[i - 1]) || x === 'pranav' || x === 'pranab' || x === 'pranay')) return true;
    }
    return false;
  }
  const pill = document.createElement('button'); pill.type = 'button'; pill.id = 'wkPill'; pill.hidden = true;
  pill.setAttribute('aria-label', 'Voice wake is on. Click to open Pranav'); document.body.appendChild(pill);
  const wst = document.createElement('style'); wst.textContent = '#wkPill{position:fixed;right:104px;bottom:34px;z-index:39;max-width:min(300px,calc(100vw - 150px));padding:8px 14px;border:1px solid var(--line);border-radius:999px;background:var(--card);color:var(--text);font:600 12.5px/1.3 inherit;box-shadow:0 8px 22px rgba(19,51,49,.22);cursor:pointer;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}#wkPill[hidden]{display:none}#wkPill small{font-weight:500;color:var(--muted)}#wkPill i{display:inline-block;width:8px;height:8px;border-radius:50%;background:#e11d48;margin-right:7px;animation:mbRec 1.2s ease-out infinite}#wkPill.err i{animation:none;background:#f59e0b}@media(max-width:600px){#wkPill{display:none}}';
  document.head.appendChild(wst);
  let wkMsg = '', wkMsgAt = 0;
  function wkSay(t) { wkMsg = String(t || '').trim().slice(-46); wkMsgAt = Date.now(); wkDraw(); }
  function wkDraw(err) {
    const show = wkOn && panel.hidden && !document.hidden;
    pill.hidden = !show; if (!show) return;
    pill.className = err ? 'err' : '';
    pill.innerHTML = '<i></i>' + (err ? esc2(err) : (wkMsg && Date.now() - wkMsgAt < 6000 ? 'Heard: <small>“' + esc2(wkMsg) + '”</small>' : 'Say “Hey Pranav” <small>· listening</small>'));
  }
  const esc2 = t => String(t).replace(/[<>&"]/g, c => ({'<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;'}[c]));
  pill.onclick = () => { openPanel(true); };
  function wkStop() { if (wkRec) { const r = wkRec; wkRec = null; wkRun = false; r.onend = r.onerror = r.onresult = null; try { r.abort(); } catch (e) {} } }
  function wkStart() {
    if (!SR || wkRun || !wkOn) return;
    wkRun = true; wkLast = Date.now(); wkDraw();
    const r = new SR(); wkRec = r; r.lang = 'en-IN'; r.continuous = true; r.interimResults = true; r.maxAlternatives = 3;
    r.onresult = ev => {
      for (let i = ev.resultIndex; i < ev.results.length; i++) for (let k = 0; k < ev.results[i].length; k++) {
        { const tx = ev.results[i][k].transcript; if (k === 0) wkSay(tx); if (wkMatch(tx)) { wkStop(); wkHeard(); return; } }
      }
    };
    r.onerror = ev => {
      const e = ev && ev.error;
      if (e === 'not-allowed' || e === 'service-not-allowed') { wkOn = false; try { localStorage.setItem(WKEY, '0'); } catch (x) {} wkPaint(); micNote('Mic is blocked, so “Hey Pranav” is off. Allow the microphone in the address bar and turn it on again 🎙️', 6000); }
      else if (e !== 'no-speech' && e !== 'aborted') { wkFail++; wkDraw(e === 'network' ? 'Voice needs internet' : e === 'audio-capture' ? 'No microphone found' : 'Voice error: ' + e); }
    };
    r.onend = () => { if (wkRec !== r) return; wkRec = null; wkRun = false; };
    try { r.start(); } catch (e) { wkRec = null; wkRun = false; wkFail++; }
  }
  function wkHeard() {
    try { document.dispatchEvent(new MouseEvent('mousemove')); } catch (e) {}
    lastGreet = Date.now(); S.speakNext = true; try { window.PPSound && window.PPSound.play('ding'); } catch (e) {}
    if (panel.hidden) openPanel(true);
    setTimeout(() => { respond({h: 'Did you just call me? 👀', mood: 'happy', chips: baseChips()}); input.focus(); }, 350);
  }
  // keep it running only while the page is visible, the chat is closed and the mic button is idle
  setInterval(() => {
    const want = wkOn && !!SR && panel.hidden && !listening && !document.hidden && !window.__ppSpeaking;
    wkDraw(); if (!want) { if (wkRec) wkStop(); return; }
    if (!wkRun && Date.now() - wkLast > (wkFail > 3 ? 8000 : 700)) wkStart();
  }, 700);
  if (SR && wkItem) wkItem.hidden = false;
  wkPaint();
  window.__ppWake = {
    toggle() {
      wkOn = !wkOn; try { localStorage.setItem(WKEY, wkOn ? '1' : '0'); } catch (e) {} wkPaint(); wkFail = 0;
      if (wkOn) { wkStart(); micNote('Listening for “Hey Pranav” while this tab is open 🎙️', 4500); } else wkStop();
    }
  };
  input.addEventListener('keydown', e => { if (e.key.length === 1 || e.key === 'Backspace') S.voiceDraft = false; });
  g('mbSpk').onclick = () => { if (window.PPSound) { const v = window.PPSound.setAll(!window.PPSound.all); micNote(v ? 'I will read every reply aloud 🔊' : 'I will read replies aloud only when you speak 🎙️', 3500); if (!v) window.PPSound.stop(); } };
  try { window.__ppSoundPaint && window.__ppSoundPaint(); } catch (e) {}
  g('mbForm').onsubmit = e => { e.preventDefault(); send(input.value); };
  chips.addEventListener('click', e => { const b = e.target.closest('button[data-q]'); if (b) send(b.dataset.q); });
  msgs.addEventListener('click', e => {
    const q = e.target.closest('button[data-q]'); if (q) { send(q.dataset.q); return; }
    const pk = e.target.closest('button[data-pick]');
    if (pk && S.pending && S.pending.t === 'which') {
      const r = allRows.find(x => String(x.ID) === pk.dataset.pick); if (!r) return;
      const then = S.pending.then; S.pending = null; S.deal = r; S.via = S.viaNext || 'name'; addMe(nameOf(r) + ' · #' + r.ID);
      think(() => then(r), 350); return;
    }
    const nv = e.target.closest('button[data-nav]'); if (nv) { location.hash = nv.dataset.nav; return; }
    const a = e.target.closest('button[data-act]');
    if (a) {
      const act = a.dataset.act;
      if (act === 'clearf') { GF = {}; gfSave(); try { page = 0; } catch (x) {} renderRoute(false); a.textContent = '✓ Cleared'; a.disabled = true; return; }
      if (act === 'reapply') { if (LASTGF) { applyGF(LASTGF.gf, false); a.textContent = '✓ Applied'; a.disabled = true; } return; }
      if (act === 'yes' || act === 'no') { if (S.busy) return; a.closest('.mbact').querySelectorAll('button').forEach(b => { b.disabled = true; }); send(act === 'yes' ? 'Yes' : 'No'); }
      else if (act === 'edit') { const r = allRows.find(x => String(x.ID) === a.dataset.id); if (r) { think(() => guideEdit([r], understand('edit this deal'), S.via || 'name'), 300); } }
      else if (act === 'form') { const r = allRows.find(x => String(x.ID) === a.dataset.id); if (r && typeof openEdit === 'function') { try { openEdit(r); } catch (x) {} } }
      else if (act === 'copyq') { try { navigator.clipboard.writeText('Pranav could not answer: “' + S.asked + '”. Please train it.'); a.textContent = '✓ Copied'; } catch (x) { a.textContent = 'Copy failed'; } }
      return;
    }
    const nl = e.target.closest('a.mbnum');
    if (nl) { e.preventDefault(); location.hash = hashFor(null, {bot: nl.dataset.sel}, true); setTimeout(() => { try { openPanel(false); } catch (x) {} }, 150); return; }
    const dl = e.target.closest('a[data-deal]');
    if (dl) { e.preventDefault(); const r = allRows.find(x => String(x.ID) === dl.dataset.deal); if (r) { S.deal = r; send('status of deal ' + r.ID); } }
  });

  window.__mbFACE = FACE;
  window.__monkeyBot = {parseFilters: parseFilters, valueDict: valueDict, doCount: doCount, brain: brain, understand: understand, findDeals: findDeals, nlDate: nlDate, state: S, parseEdit: parseEdit, fixTok: fixTok};
  setTimeout(() => { if (panel.hidden) openB.classList.add('hint'); setTimeout(() => openB.classList.remove('hint'), 4200); }, 2500);
})();

</script>
<!--/MONKEY-->
<!--LIFE-->
<script>
(function () {
  'use strict';
  if (window.__monkeyLife) return;
  const D = document, g = id => D.getElementById(id);
  const FACE = window.__mbFACE || '';
  const reduce = !!(window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches);
  const LS = 'pp_monkey_rest';
  let rest = false; try { rest = localStorage.getItem(LS) === '1'; } catch (e) {}
  const clamp = (v, a, b) => Math.max(a, Math.min(b, v));
  const rand = (a, b) => a + Math.random() * (b - a);
  const pick = a => a[Math.floor(Math.random() * a.length)];
  const small = () => innerWidth <= 600;

  /* ================= the monkey (SVG rig) ================= */
  function rig(extra) {
    const fur = '#8a5632';
    return '<svg class="mkr mbx' + (extra ? ' ' + extra : '') + '" viewBox="0 0 160 205" aria-hidden="true"><g class="all">' +
      '<g class="tail"><path d="M98 172C146 178 158 130 134 112C124 103 132 90 146 97" fill="none" stroke="' + fur + '" stroke-width="11" stroke-linecap="round"/><circle cx="146" cy="97" r="6.5" fill="#f4d5b0"/></g>' +
      '<g class="lgL"><path d="M64 158L60 184" stroke="' + fur + '" stroke-width="17" stroke-linecap="round"/><ellipse cx="55" cy="194" rx="17" ry="9" fill="#9a6338"/><ellipse cx="54" cy="196" rx="10" ry="4" fill="#f0c7a0"/></g>' +
      '<g class="lgR"><path d="M96 158L100 184" stroke="' + fur + '" stroke-width="17" stroke-linecap="round"/><ellipse cx="105" cy="194" rx="17" ry="9" fill="#9a6338"/><ellipse cx="106" cy="196" rx="10" ry="4" fill="#f0c7a0"/></g>' +
      '<g class="bod"><ellipse cx="80" cy="134" rx="34" ry="42" fill="#9a6338"/><ellipse cx="80" cy="142" rx="21" ry="29" fill="#f4d5b0"/></g>' +
      '<g class="hd"><g transform="translate(80 60) scale(.56) translate(-100 -102)">' + FACE + '</g>' +
      '<g class="xhat"><path d="M54 33Q58 -4 100 6Q96 20 108 33Z" fill="#dc2626"/><rect x="48" y="28" width="64" height="10" rx="5" fill="#fff"/><circle cx="102" cy="6" r="6.5" fill="#fff"/></g>' +
      '<g class="pty"><path d="M80 -8L62 32H98Z" fill="#25d8cc"/><path d="M70 14l20 0M66 24l28 0" stroke="#fde68a" stroke-width="3"/><circle cx="80" cy="-9" r="5" fill="#fcd34d"/></g>' +
      '<g class="gcap"><path d="M80 4L130 23L80 42L30 23Z" fill="#173230"/><path d="M58 33v11c0 5 10 9 22 9s22-4 22-9V33l-22 9z" fill="#234c49"/><path d="M122 27v24" stroke="#fbbf24" stroke-width="3"/><circle cx="122" cy="53" r="4.5" fill="#fbbf24"/></g><g class="band"><rect x="40" y="30" width="80" height="8" rx="4" fill="#ff9933"/><rect x="40" y="35" width="80" height="4" fill="#fff"/><rect x="40" y="38" width="80" height="3" rx="1.5" fill="#138808"/></g></g>' +
      '<g class="arL"><path d="M54 106C37 122 30 140 34 158" fill="none" stroke="' + fur + '" stroke-width="14" stroke-linecap="round"/><circle cx="34" cy="161" r="10.5" fill="#9a6338"/><circle cx="34" cy="163" r="5.5" fill="#f0c7a0" opacity=".7"/></g>' +
      '<g class="arR"><path d="M106 106C123 122 130 140 126 158" fill="none" stroke="' + fur + '" stroke-width="14" stroke-linecap="round"/><circle cx="126" cy="161" r="10.5" fill="#9a6338"/><circle cx="126" cy="163" r="5.5" fill="#f0c7a0" opacity=".7"/><path class="bn" d="M116 170c-9-4-12-20-3-29c3-3 7-2 7 1c-4 9 0 17 9 21c3 2 2 6-1 7z" fill="#fcd34d" stroke="#b45309" stroke-width="1.6" stroke-linejoin="round"/><g class="cup"><path d="M114 150h22l-3 20h-16z" fill="#fff" stroke="#9a6338" stroke-width="2"/><path d="M136 154q9 2 0 11" fill="none" stroke="#9a6338" stroke-width="2.5"/><path d="M114 156h22" stroke="#92400e" stroke-width="4"/><path class="stm" d="M121 146q-3-5 0-10M129 146q-3-5 0-10" stroke="#9ca3af" stroke-width="2" fill="none" stroke-linecap="round"/></g></g>' +
      '<g class="diya"><circle cx="140" cy="190" r="22" fill="#fbbf24" opacity=".18"/><path d="M126 192h28q-2 10-14 10t-14-10z" fill="#b45309"/><path class="fl" d="M140 190q-8-11 0-24q8 13 0 24z" fill="#fbbf24"/></g>' +
      '<g class="flag"><rect x="146" y="118" width="3" height="84" fill="#8b5a2b"/><rect x="149" y="120" width="24" height="7" fill="#ff9933"/><rect x="149" y="127" width="24" height="7" fill="#fff"/><rect x="149" y="134" width="24" height="7" fill="#138808"/><circle cx="161" cy="130.5" r="2.4" fill="none" stroke="#1e3a8a" stroke-width=".8"/></g>' +
      '<text class="zz" x="118" y="30" font-size="24" font-weight="800" fill="#2cdace">z</text><text class="zz z2" x="134" y="12" font-size="30" font-weight="800" fill="#75e8e0">Z</text>' +
      '</g></svg>';
  }

  const CSS = '' +
    '#mkLife{position:fixed;left:0;top:0;z-index:40;pointer-events:none;will-change:transform;transform-origin:50% 100%}' +
    '#mkLife[hidden]{display:none}#mkLife.paused *{animation-play-state:paused!important}' +
    '#mkLife .mkr,.ppw-mk .mkr{display:block;width:100%;height:100%;overflow:visible;pointer-events:none;filter:drop-shadow(0 8px 8px rgba(19,58,55,.28))}' +
    '#mkLife .mkr .all{pointer-events:auto;cursor:pointer}#mkLife.drag .mkr .all{cursor:grabbing}' +
    '.mkr g{transform-box:view-box}' +
    '.mkr .tail{transform-origin:98px 172px;animation:mkTail 2.8s ease-in-out infinite}@keyframes mkTail{0%,100%{transform:rotate(-6deg)}50%{transform:rotate(10deg)}}' +
    '.mkr .arL{transform-origin:54px 106px;transition:transform .25s}.mkr .arR{transform-origin:106px 106px;transition:transform .25s}' +
    '.mkr .lgL{transform-origin:64px 158px;transition:transform .25s}.mkr .lgR{transform-origin:96px 158px;transition:transform .25s}' +
    '.mkr .hd{transform-origin:80px 94px;transition:transform .3s}.mkr .all{transform-origin:80px 200px;transition:transform .25s}' +
    '.mkr .bod{transform-origin:80px 176px;animation:mkBreath 3.2s ease-in-out infinite}@keyframes mkBreath{0%,100%{transform:scaleY(1)}50%{transform:scaleY(1.03)}}' +
    '.mkr .bn,.mkr .zz{display:none}' +
    '.mkr .mbp{transform:translate(calc(var(--ex,0)*1px),calc(var(--ey,0)*1px))}' +
    /* walking */
    '.mkr.walk .all{animation:mkBob .27s ease-in-out infinite alternate}@keyframes mkBob{from{transform:translateY(0)}to{transform:translateY(-5px)}}' +
    '.mkr.walk .lgL{animation:mkLegA .54s ease-in-out infinite alternate}.mkr.walk .lgR{animation:mkLegA .54s ease-in-out infinite alternate-reverse}@keyframes mkLegA{from{transform:rotate(-28deg)}to{transform:rotate(28deg)}}' +
    '.mkr.walk .arL{animation:mkArmA .54s ease-in-out infinite alternate-reverse}.mkr.walk .arR{animation:mkArmB .54s ease-in-out infinite alternate}@keyframes mkArmA{from{transform:rotate(-26deg)}to{transform:rotate(26deg)}}@keyframes mkArmB{from{transform:rotate(26deg)}to{transform:rotate(-26deg)}}' +
    '.mkr.walk .tail{animation-duration:.9s}' +
    /* leaping / hanging */
    '.mkr.leap .arL{transform:rotate(158deg)}.mkr.leap .arR{transform:rotate(-158deg)}.mkr.leap .lgL{transform:rotate(22deg)}.mkr.leap .lgR{transform:rotate(-22deg)}.mkr.leap .tail{animation-duration:.5s}' +
    /* climbing */
    '.mkr.climb .arL{animation:mkClA .6s ease-in-out infinite alternate}.mkr.climb .arR{animation:mkClB .6s ease-in-out infinite alternate}@keyframes mkClA{from{transform:rotate(138deg)}to{transform:rotate(178deg)}}@keyframes mkClB{from{transform:rotate(-178deg)}to{transform:rotate(-138deg)}}' +
    '.mkr.climb .lgL{animation:mkLgA .6s ease-in-out infinite alternate}.mkr.climb .lgR{animation:mkLgA .6s ease-in-out infinite alternate-reverse}@keyframes mkLgA{from{transform:rotate(-8deg)}to{transform:rotate(24deg)}}' +
    '.mkr.cling .arL{transform:rotate(168deg)}.mkr.cling .arR{transform:rotate(-168deg)}.mkr.cling .lgL{transform:rotate(12deg)}.mkr.cling .lgR{transform:rotate(-12deg)}' +
    /* sitting */
    '.mkr.sit .all,.mkr.sleep .all{transform:translateY(22px)}.mkr.sit .lgL,.mkr.sleep .lgL{transform:rotate(60deg)}.mkr.sit .lgR,.mkr.sleep .lgR{transform:rotate(-60deg)}.mkr.sit .arL,.mkr.sleep .arL{transform:rotate(14deg)}.mkr.sit .arR,.mkr.sleep .arR{transform:rotate(-14deg)}' +
    /* gestures */
    '.mkr.wave .arR{animation:mkWave .45s ease-in-out infinite alternate}@keyframes mkWave{from{transform:rotate(-132deg)}to{transform:rotate(-176deg)}}' +
    '.mkr.scratch .arR{animation:mkScr .22s ease-in-out infinite alternate}@keyframes mkScr{from{transform:rotate(-168deg)}to{transform:rotate(-184deg)}}.mkr.scratch .hd{transform:rotate(-6deg)}' +
    '.mkr.eat .arR{transform:rotate(-150deg)}.mkr.eat .bn{display:block}.mkr.eat .hd{animation:mkChew .26s ease-in-out infinite alternate}@keyframes mkChew{from{transform:rotate(-2.5deg) translateY(0)}to{transform:rotate(2.5deg) translateY(1.5px)}}' +
    '.mkr.yawn .mbm1{display:none}.mkr.yawn .mbm4{display:block;animation:mkYawn 1.5s ease-in-out both;transform-box:fill-box;transform-origin:50% 0}@keyframes mkYawn{0%{transform:scaleY(.1)}35%,70%{transform:scaleY(1.3)}100%{transform:scaleY(.1)}}.mkr.yawn .mbe{transform:scaleY(.25)}.mkr.yawn .arR{transform:rotate(-160deg)}' +
    '.mkr.talk .mbm1{display:none}.mkr.talk .mbm4{display:block;animation:mkTalk .22s ease-in-out infinite alternate;transform-box:fill-box;transform-origin:50% 0}@keyframes mkTalk{from{transform:scaleY(.25)}to{transform:scaleY(.85)}}' +
    '.mkr.sleep .mbe{animation:none;transform:scaleY(.1)}.mkr.sleep .hd{transform:rotate(16deg) translateY(2px)}.mkr.sleep .zz{display:block;animation:mkZ 2.4s ease-in-out infinite}.mkr.sleep .z2{animation-delay:1.2s}@keyframes mkZ{0%{opacity:0;transform:translateY(8px)}30%,70%{opacity:1}100%{opacity:0;transform:translateY(-10px)}}' +
    '.mkr.drag .arR{transform:rotate(-176deg)}.mkr.drag .arL{animation:mkDrA .5s ease-in-out infinite alternate}@keyframes mkDrA{from{transform:rotate(6deg)}to{transform:rotate(34deg)}}.mkr.drag .lgL{animation:mkDg .7s ease-in-out infinite alternate}.mkr.drag .lgR{animation:mkDg .7s ease-in-out infinite alternate-reverse}@keyframes mkDg{from{transform:rotate(-16deg)}to{transform:rotate(16deg)}}.mkr.drag .mbm1{display:none}.mkr.drag .mbm4{display:block}' +
    '.mkr.cheer .arL{transform:rotate(160deg)}.mkr.cheer .arR{transform:rotate(-160deg)}.mkr.cheer .all{animation:mkHop .38s ease-out 4 alternate}@keyframes mkHop{from{transform:translateY(0)}to{transform:translateY(-16px)}}' +
    '.mkr.land .all{animation:mkLand .3s ease-out}@keyframes mkLand{0%{transform:translateY(0) scaleY(1)}35%{transform:translateY(8px) scaleY(.88)}100%{transform:none}}' +
    '.mkr.dance .all{animation:mkDance .45s ease-in-out infinite alternate}@keyframes mkDance{from{transform:rotate(-5deg) translateY(0)}to{transform:rotate(5deg) translateY(-8px)}}.mkr.dance .arL{animation:mkClA .45s ease-in-out infinite alternate}.mkr.dance .arR{animation:mkClB .45s ease-in-out infinite alternate-reverse}.mkr.dance .lgL{animation:mkLegA .45s ease-in-out infinite alternate}.mkr.dance .lgR{animation:mkLegA .45s ease-in-out infinite alternate-reverse}' +
    '.mkr.stretch .arL{transform:rotate(172deg)}.mkr.stretch .arR{transform:rotate(-172deg)}.mkr.stretch .all{transform:translateY(-4px) scaleY(1.05)}.mkr.stretch .hd{transform:rotate(-7deg)}' +
    '.mkr.tailplay .tail{animation:mkTailF .22s ease-in-out infinite alternate}@keyframes mkTailF{from{transform:rotate(-24deg)}to{transform:rotate(28deg)}}.mkr.tailplay .hd{transform:rotate(15deg)}' +
    '.mkr.clap .arL{animation:mkClapL .3s ease-in-out infinite alternate}.mkr.clap .arR{animation:mkClapR .3s ease-in-out infinite alternate}@keyframes mkClapL{from{transform:rotate(-12deg)}to{transform:rotate(-44deg)}}@keyframes mkClapR{from{transform:rotate(12deg)}to{transform:rotate(44deg)}}' +
    '.mkr .xhat,.mkr .pty,.mkr .band,.mkr .diya,.mkr .flag,.mkr .cup,.mkr .gcap{display:none}.mkr.sx-xmas .xhat,.mkr.sx-party .pty,.mkr.sx-india .band,.mkr.sx-india .flag,.mkr.sx-diwali .diya,.mkr.coffee .cup{display:block}.mkr .diya .fl{transform-box:fill-box;transform-origin:50% 100%;animation:mkFlame .5s ease-in-out infinite alternate}@keyframes mkFlame{from{transform:scale(1,1) skewX(-4deg)}to{transform:scale(.88,1.15) skewX(5deg)}}' +
    '.mkr.coffee .arR{transform:rotate(-128deg);animation:mkSip 2.2s ease-in-out infinite}@keyframes mkSip{0%,55%,100%{transform:rotate(-128deg)}70%,85%{transform:rotate(-152deg)}}.mkr.coffee .bn{display:none}.mkr.coffee .stm{animation:mkSteam 1.6s ease-in-out infinite}@keyframes mkSteam{0%{opacity:0;transform:translateY(4px)}50%{opacity:.9}100%{opacity:0;transform:translateY(-6px)}}' +
    '.mkr.fan .arR{animation:mkWave .16s ease-in-out infinite alternate}.mkr.fan .hd{animation:mkChew .3s ease-in-out infinite alternate}.mkr.fan .all{filter:saturate(1.2)}' +
    '#fxBan{position:fixed;z-index:44;display:flex;align-items:center;gap:8px;padding:5px 14px 5px 8px;border-radius:999px;background:var(--card,#fff);color:var(--text,#222);border:1px solid var(--line,#ddd);box-shadow:0 8px 22px rgba(29,90,86,.22);font:700 12.5px system-ui,sans-serif;cursor:grab;touch-action:none;user-select:none;-webkit-user-select:none;white-space:nowrap}' +
    '#fxBan .b{font-size:24px;line-height:1;display:inline-block;animation:mkBanB .9s ease-in-out infinite alternate;filter:drop-shadow(0 3px 3px rgba(0,0,0,.25))}@keyframes mkBanB{from{transform:translateY(0) rotate(-8deg)}to{transform:translateY(-4px) rotate(8deg)}}' +
    '#fxBan.drag{cursor:grabbing;z-index:80;padding:0;background:transparent;border:0;box-shadow:none}#fxBan.drag small{display:none}#fxBan.drag .b{font-size:34px;animation:none}#fxBan[hidden]{display:none}' +
    '@media(max-width:700px){#fxBan{display:none}}' +
    /* speech bubble */
    '.mkbub{position:absolute;left:50%;bottom:calc(100% + 2px);transform:translateX(-50%) scale(.6);transform-origin:50% 100%;width:max-content;max-width:210px;padding:8px 12px;border-radius:14px;background:var(--card,#fff);color:var(--text,#222);border:1px solid var(--line,#ddd);box-shadow:0 10px 26px rgba(20,64,61,.3);font:600 13px/1.35 system-ui,-apple-system,Segoe UI,sans-serif;opacity:0;pointer-events:none;transition:opacity .18s,transform .28s cubic-bezier(.3,1.5,.5,1);z-index:2}' +
    '.mkbub.on{opacity:1;transform:translateX(-50%) scale(1)}.mkbub::after{content:"";position:absolute;left:50%;bottom:-7px;width:12px;height:12px;background:var(--card,#fff);border-right:1px solid var(--line,#ddd);border-bottom:1px solid var(--line,#ddd);transform:translateX(-50%) rotate(45deg)}' +
    '.mkbub.l{left:-8px;transform:scale(.6);transform-origin:20% 100%}.mkbub.l.on{transform:scale(1)}.mkbub.l::after{left:34px}.mkbub.r{left:auto;right:-8px;transform:scale(.6);transform-origin:80% 100%}.mkbub.r.on{transform:scale(1)}.mkbub.r::after{left:auto;right:28px;transform:rotate(45deg)}' +
    '.mkbub.below{bottom:auto;top:calc(100% - 8px)}.mkbub.below::after{bottom:auto;top:-7px;transform:translateX(-50%) rotate(225deg)}' +
    'body.mklife .mbl{display:none!important}' +
    '@media(max-width:600px){#mkLife.hide{display:none}}' +
    '@media(prefers-reduced-motion:reduce){.mkr *{animation:none!important}}';

  const st = D.createElement('style'); st.textContent = CSS; D.head.appendChild(st);

  /* ================= state ================= */
  const el = D.createElement('div'); el.id = 'mkLife';
  el.innerHTML = '<div class="mkbub" id="mkBub" role="status" aria-live="polite"></div>' + rig('');
  D.body.appendChild(el); D.body.classList.add('mklife');
  const svg = el.querySelector('.mkr'), bub = g('mkBub');
  const openB = g('mbOpen'), panel = g('mbPanel');
  let W = 96, H = 123, SC = 1;
  const P = {x: innerWidth - 100, y: innerHeight - 4, s: 1};
  let face = 1, rot = 0, pose = '', fx = '', moodCls = '', loc = 'ground', perchEl = null, perchTop = 0, edgeSide = 'right';
  const sfx = n => { try { window.PPSound && window.PPSound.play(n); } catch (e) {} };
  let tok = 0, chatOpen = false, dragging = false, lastAct = Date.now(), asleep = false, alive = true, started = false, welcomeOn = false;
  const sl = new Set();
  let season = '', acting = false;
  function seasonNow() {
    let q = ''; try { q = new URLSearchParams(location.search).get('mkseason') || ''; } catch (e) {}
    if (q) return 'sx-' + q;
    const d = new Date(), m = d.getMonth() + 1, dy = d.getDate(), y = d.getFullYear();
    if ((y === 2026 && m === 11 && dy >= 3 && dy <= 10) || (y === 2027 && ((m === 10 && dy >= 27) || (m === 11 && dy <= 3)))) return 'sx-diwali';
    if (m === 12 && dy >= 20 && dy <= 26) return 'sx-xmas';
    if ((m === 12 && dy >= 30) || (m === 1 && dy <= 2)) return 'sx-party';
    if ((m === 8 && dy >= 13 && dy <= 15) || (m === 1 && dy >= 24 && dy <= 26)) return 'sx-india';
    return '';
  }

  function size() { SC = small() ? .8 : 1; W = 96 * SC; H = 123 * SC; el.style.width = W + 'px'; el.style.height = H + 'px'; }
  const ground = () => innerHeight - 4;
  function render() {
    el.style.transform = 'translate3d(' + (P.x - W / 2).toFixed(1) + 'px,' + (P.y - H).toFixed(1) + 'px,0)' + (P.s !== 1 ? ' scale(' + P.s.toFixed(3) + ')' : '');
    svg.style.transform = 'scaleX(' + face + ') rotate(' + rot.toFixed(1) + 'deg)';
  }
  function paint() { svg.setAttribute('class', ('mkr mbx ' + pose + ' ' + fx + ' ' + moodCls + ' ' + season).replace(/\s+/g, ' ').trim()); }
  function setPose(p) { pose = p || ''; paint(); }
  function setFx(f, ms) { fx = f || ''; paint(); clearTimeout(setFx.t); if (f && ms) setFx.t = setTimeout(() => { fx = ''; paint(); }, ms); }
  function setMood(m, ms) { moodCls = m && m !== 'idle' ? m : ''; paint(); clearTimeout(setMood.t); if (moodCls && ms) setMood.t = setTimeout(() => { moodCls = ''; paint(); }, ms); }
  function cancel() { tok++; Array.from(sl).forEach(f => f()); }
  function sleep(ms) {
    const my = tok;
    return new Promise(res => { const t = setTimeout(done, ms); function done() { clearTimeout(t); sl.delete(done); res(my === tok); } sl.add(done); });
  }
  function tween(ms, step) {
    const my = tok;
    return new Promise(res => {
      let acc = 0, last = performance.now();
      (function f(now) {
        if (my !== tok) { res(false); return; }
        const dt = Math.min(50, now - last); last = now; if (!D.hidden) acc += dt;
        const k = Math.min(1, acc / ms); step(k);
        if (k >= 1) { res(true); return; }
        requestAnimationFrame(f);
      })(last);
    });
  }

  /* ================= speech bubble ================= */
  function say(html, ms) {
    bub.innerHTML = html; bub.className = 'mkbub';
    const cls = [P.x < 130 ? 'l' : P.x > innerWidth - 130 ? 'r' : '', P.y - H < 80 ? 'below' : ''].filter(Boolean).join(' ');
    bub.className = 'mkbub ' + cls; void bub.offsetWidth; bub.classList.add('on');
    clearTimeout(say.t); say.t = setTimeout(hideBub, ms || 3400);
  }
  function hideBub() { bub.classList.remove('on'); }

  /* ================= what is in the way? ================= */
  function visibleRects(sel) {
    const out = [];
    D.querySelectorAll(sel).forEach(n => {
      const r = n.getBoundingClientRect();
      if (r.width < 4 || r.height < 4 || r.bottom < 0 || r.top > innerHeight || r.right < 0 || r.left > innerWidth) return;
      out.push(r);
    });
    return out;
  }
  let blockCache = null, blockAt = 0;
  function blockers() {
    const now = Date.now();
    if (blockCache && now - blockAt < 350) return blockCache;
    blockAt = now;
    blockCache = visibleRects('.tablewrap,table,input,textarea,select,.udrop:not([hidden]),#mbPanel:not([hidden]),.side');
    return blockCache;
  }
  function freeAt(x, y) {
    const l = x - W * .45, r = x + W * .45, t = y - H, b = y;
    if (l < 0 || r > innerWidth || t < 6 || b > innerHeight + 2) return false;
    return !blockers().some(q => !(q.right < l || q.left > r || q.bottom < t || q.top > b));
  }
  function pathFree(x0, y0, x1, y1) {
    const n = Math.max(2, Math.ceil(Math.hypot(x1 - x0, y1 - y0) / 50));
    for (let i = 0; i <= n; i++) if (!freeAt(x0 + (x1 - x0) * i / n, y0 + (y1 - y0) * i / n)) return false;
    return true;
  }
  const uiBusy = () => !!D.querySelector('.pfov:not([hidden]),.pkov:not([hidden]),.gqov:not([hidden]),#modal:not([hidden]),.wapop:not([hidden])');

  /* ================= movement ================= */
  async function walkTo(x, speed) {
    const x0 = P.x, d = Math.abs(x - x0);
    if (d < 6) return true;
    face = x > x0 ? -1 : 1; setPose('walk');
    const ok = await tween(d / (speed || 92) * 1000, k => { P.x = x0 + (x - x0) * k; render(); });
    if (ok) setPose('');
    return ok;
  }
  async function leapTo(x, y, arc, spin) {
    const x0 = P.x, y0 = P.y, s0 = P.s, d = Math.hypot(x - x0, y - y0);
    face = x > x0 ? -1 : 1; if (Math.abs(x - x0) < 4) face = face || 1;
    setPose('leap'); hideBub();
    const ok = await tween(clamp(d * 1.25, 650, 1500), k => {
      P.x = x0 + (x - x0) * k; P.y = y0 + (y - y0) * k - 4 * arc * k * (1 - k); P.s = s0 + (1 - s0) * k;
      rot = spin ? spin * 360 * k * -face : Math.sin(Math.PI * k) * 8 * -face; render();
    });
    rot = 0; P.s = 1; render();
    if (ok) { setPose(''); setFx('land', 320); }
    return ok;
  }
  async function climbTo(y) {
    const y0 = P.y; setPose('climb');
    const ok = await tween(Math.abs(y - y0) / 62 * 1000, k => { P.y = y0 + (y - y0) * k; render(); });
    if (ok) setPose('cling');
    return ok;
  }
  const nearHome = () => Math.abs(P.x - (innerWidth - 80)) < 24 && Math.abs(P.y - ground()) < 10;
  async function goHome() {
    loc = 'ground';
    const hx = innerWidth - (small() ? 56 : 82), hy = ground();
    if (P.y < hy - 40 || Math.abs(P.x - hx) > 360) { if (!await leapTo(hx, hy, 90, 0)) return; } else if (!await walkTo(hx)) return;
    face = 1; render();
  }

  /* ================= what the monkey does ================= */
  async function roamGround() {
    for (let i = 0; i < 10; i++) {
      const x = rand(W, innerWidth - W), y = ground();
      if (Math.abs(x - P.x) < 120) continue;
      if (!pathFree(P.x, y, x, y)) continue;
      loc = 'ground'; await walkTo(x);
      if (Math.random() < .35 && !chatOpen) await lookAround();
      return;
    }
    await doIdle(true);
  }
  async function toGround() {
    for (let i = 0; i < 10; i++) {
      const x = clamp(P.x + rand(-300, 300), W, innerWidth - W), y = ground();
      if (!freeAt(x, y)) continue;
      loc = 'ground'; perchEl = null; await leapTo(x, y, 70, Math.random() < .3 ? 1 : 0); return;
    }
    loc = 'ground'; perchEl = null; await leapTo(innerWidth - 82, ground(), 70, 0);
  }
  async function perchOn() {
    const els = Array.from(D.querySelectorAll('.kpi,a.tile,.hero')).filter(n => { const r = n.getBoundingClientRect(); return r.width > 110 && r.top > H + 16 && r.top < innerHeight - 160 && r.bottom > 0 && r.right > 0 && r.left < innerWidth; });
    for (let i = 0; i < 8 && els.length; i++) {
      const n = pick(els), r = n.getBoundingClientRect();
      if (r.width < W + 30) continue;
      const x = rand(r.left + W / 2 + 6, r.right - W / 2 - 6), y = r.top + 3;
      if (!freeAt(x, y)) continue;
      const ok = await leapTo(x, y, rand(90, 170), Math.random() < .35 ? 1 : 0);
      if (ok) { loc = 'perch'; perchEl = n; perchTop = r.top; face = Math.random() < .5 ? 1 : -1; render(); }
      return;
    }
    await doIdle(true);
  }
  async function edgeOn() {
    const side = Math.random() < .75 ? 'right' : 'left';
    const x = side === 'right' ? innerWidth - W * .5 - 2 : W * .5 + 2;
    const y = clamp(ground() - rand(60, 260), H + 20, ground());
    if (!pathFree(x, ground(), x, y)) { await roamGround(); return; }
    face = side === 'right' ? -1 : 1;
    if (await leapTo(x, ground() - 30, 80, 0)) { loc = 'edge'; edgeSide = side; face = side === 'right' ? -1 : 1; setPose('cling'); render(); await climbTo(y); }
  }
  async function climbMove() {
    for (let i = 0; i < 6; i++) {
      const y = rand(H + 20, ground());
      if (Math.abs(y - P.y) < 70 || !pathFree(P.x, P.y, P.x, y)) continue;
      await climbTo(y); return;
    }
    await toGround();
  }
  async function lookAround() {
    const seq = [[-5, 0], [5, 0], [0, -4], [4, 2], [0, 0]];
    for (const s of seq) { svg.style.setProperty('--ex', s[0]); svg.style.setProperty('--ey', s[1]); if (!await sleep(520)) break; }
    lastLookTrack = 0;
  }
  const IDLES = [
    {p: 'scratch', t: 2600, say: ['Hmm, an itch 🙈', 'Hmm, where was I… 🤔']},
    {p: 'eat', t: 3800, say: ['Nom nom nom 🍌', 'Banana break! 🍌']},
    {p: 'yawn', t: 1600, say: ['Aaaah-hm… 🥱']},
    {p: 'wave', t: 2200, say: ['Hi there! 👋', 'Hello again!']},
    {p: 'sit', t: 4200, say: []}
  ];
  async function doIdle(sit) {
    if (Date.now() - lastAct > 28000 && loc !== 'edge') { await napTime(); return; }
    const it = (sit && Math.random() < .5) ? IDLES[4] : pick(IDLES.slice(0, 5));
    if (loc === 'edge' && it.p === 'sit') { await sleep(1500); return; }
    const keep = loc === 'edge' ? 'cling' : '';
    if (it.p === 'sit' && loc === 'edge') return;
    setPose(it.p); if (it.say.length && Math.random() < .7) say(pick(it.say), it.t + 600);
    if (await sleep(it.t)) setPose(keep);
  }
  async function napTime() {
    asleep = true; setPose('sleep'); say('Zzz… 😴', 2600);
    const my = tok;
    while (my === tok && Date.now() - lastAct > 5000 && !D.hidden) { if (!await sleep(500)) break; }
    asleep = false;
  }
  function wake() {
    if (!asleep) return;
    asleep = false; cancel(); setPose('yawn'); say('Oh! You’re back 👀', 2400);
    setTimeout(() => { if (!chatOpen && !dragging) setPose(loc === 'edge' ? 'cling' : ''); }, 1500);
  }

  function blocked() { return acting || rest || reduce || chatOpen || dragging || welcomeOn || uiBusy(); }
  let lastAct2 = -1;
  const ACTS = [
    {p: 'eat', t: 4400, s: ['Nom nom nom 🍌', 'Banana break! 🍌', 'Mmm… best banana ever 😋']},
    {p: 'dance', t: 4600, s: ['Dance break! 💃', 'Look at me go! 🕺']},
    {p: 'scratch', t: 2800, s: ['Ooh, an itch 🙈', 'Hmm, where was I… 🤔']},
    {p: 'wave', t: 2400, s: ['Hello hello! 👋', 'Psst, I’m here if you need me']},
    {p: 'clap', t: 2600, s: ['Great work, boss! 👏', 'Yay, numbers! 👏']},
    {p: 'stretch yawn', t: 2300, s: ['Aaah… stretch time 🥱']},
    {p: 'tailplay', t: 3600, s: ['Hehe, my tail! 🙈', 'Gotcha, tail! 🌀']},
    {p: 'sit', t: 3200, s: []},
    {p: 'cheer', t: 2000, s: ['Woohoo! 🎉']},
    {look: 1},
    {nap: 1, t: 9000, s: ['Zzz… 😴']},
    {p: 'sit', mood: 'conf', t: 3200, s: ['Hmm, which lead should I chase next? 🤔']},
    {p: 'coffee', t: 5200, s: ['Morning coffee ☕', 'Sip… ahh ☕']}
  ];
  function pool() {
    const h = new Date().getHours() + new Date().getMinutes() / 60, idx = ACTS.map((x, i) => i), add = (p, n) => { ACTS.forEach((x, i) => { if (x.p === p || (p === 'nap' && x.nap)) for (let k = 0; k < n; k++) idx.push(i); }); };
    if (h >= 6 && h < 10.5) add('coffee', 4);
    if (h >= 12 && h < 14.5) add('eat', 3);
    if (h >= 22 || h < 5) { add('nap', 3); add('stretch yawn', 2); }
    return idx;
  }
  async function doAct() {
    if (Date.now() - lastAct > 30000) { await napTime(); return; }
    const pl = pool(); let i, guard = 0; do { i = pick(pl); } while (i === lastAct2 && guard++ < 12);
    lastAct2 = i; const a = ACTS[i];
    if (a.look) { await lookAround(); return; }
    if (a.nap) { asleep = true; setPose('sleep'); say(pick(a.s), 2400); const ok = await sleep(a.t); asleep = false; if (ok) { setPose('yawn'); await sleep(1300); setPose(''); } return; }
    setPose(a.p); if (a.mood) setMood(a.mood, a.t); if (a.s.length) say(pick(a.s), a.t > 3000 ? 3200 : 2200);
    if (await sleep(a.t)) setPose('');
  }
  async function step() {
    if (blocked()) { await sleep(600); return; }
    if (!nearHome()) { await goHome(); return; }
    await doAct();
    await sleep(rand(250, 800));
  }
  async function perchWalk() {
    if (!perchEl) { await toGround(); return; }
    const r = perchEl.getBoundingClientRect();
    const x = rand(r.left + W / 2 + 6, r.right - W / 2 - 6);
    if (freeAt(x, P.y)) await walkTo(x, 70); else await doIdle(true);
  }
  async function loop() {
    while (alive) {
      if (D.hidden || !started) { await sleep(700); continue; }
      try { await step(); } catch (e) { await sleep(1200); }
    }
  }

  /* ================= being parked (resting / busy) ================= */
  async function park() {
    cancel(); await sleep(30);
    await goHome();
    if (!chatOpen && !dragging && rest) setPose('sit');
  }

  /* ================= cursor, activity ================= */
  let lastLookTrack = 0;
  D.addEventListener('mousemove', e => {
    lastAct = Date.now(); if (asleep) wake();
    const now = performance.now(); if (now - lastLookTrack < 40) return; lastLookTrack = now;
    const dx = e.clientX - P.x, dy = e.clientY - (P.y - H * .72);
    svg.style.setProperty('--ex', clamp(dx / 38, -6, 6).toFixed(2)); svg.style.setProperty('--ey', clamp(dy / 38, -5, 5).toFixed(2));
  }, {passive: true});
  ['keydown', 'pointerdown', 'wheel', 'touchstart'].forEach(ev => D.addEventListener(ev, () => { lastAct = Date.now(); if (asleep) wake(); }, {passive: true}));

  /* ================= touching the monkey ================= */
  let down = null, hoverT = 0, clickT = 0;
  const fxb = (x, y, n, em, p) => { if (window.__fx) window.__fx.burst(x, y, n, em, p); };
  async function flip() {
    cancel(); acting = true; asleep = false; hideBub(); const y0 = P.y, f = face || 1; setPose('leap'); setMood('happy', 1600);
    const ok = await tween(900, k => { P.y = y0 - 4 * 80 * k * (1 - k); rot = 360 * k * -f; render(); });
    rot = 0; P.y = y0; render(); acting = false; if (ok) { setPose(''); setFx('land', 320); say(pick(['Ta-da! 🤸', 'Wheee! 🤸‍♂️', 'Nailed it! 🏅']), 2000); }
  }
  async function feed() {
    cancel(); acting = true; asleep = false; hideBub(); setPose('eat'); setMood('happy', 3800); say('Thank you! 🍌💕', 3200);
    fxb(P.x, P.y - H * .6, 12, ['💖', '🍌', '✨'], .8);
    const okf = await sleep(3800); acting = false; if (okf) setPose('');
  }
  /* banana to drag onto the monkey */
  const ban = D.createElement('div'); ban.id = 'fxBan'; ban.innerHTML = '<span class="b">🍌</span><small>Reward Pranav — drag me to him!</small>'; ban.title = 'Want to reward Pranav? Drag this banana onto him 🍌'; D.body.appendChild(ban);
  let bd = null;
  function banHome() { ban.style.left = '92px'; ban.style.top = (innerHeight - 58) + 'px'; }
  ban.addEventListener('pointerdown', e => { bd = {id: e.pointerId}; ban.classList.add('drag'); try { ban.setPointerCapture(e.pointerId); } catch (x) {} e.preventDefault(); });
  ban.addEventListener('pointermove', e => { if (bd) { ban.style.left = (e.clientX - 18) + 'px'; ban.style.top = (e.clientY - 20) + 'px'; } });
  const banUp = e => {
    if (!bd) return; bd = null; ban.classList.remove('drag');
    const dx = e.clientX - P.x, dy = e.clientY - (P.y - H * .5);
    if (Math.hypot(dx, dy) < 80) { ban.hidden = true; feed(); setTimeout(() => { banHome(); ban.hidden = false; }, 4200); } else banHome();
  };
  ban.addEventListener('pointerup', banUp); ban.addEventListener('pointercancel', banUp);
  setInterval(() => { ban.hidden = chatOpen || dragging || welcomeOn || rest; if (!bd && !ban.hidden) banHome(); }, 600);
  svg.addEventListener('pointerdown', e => {
    if (e.button !== 0 && e.pointerType === 'mouse') return;
    down = {x: e.clientX, y: e.clientY, moved: false, id: e.pointerId, long: false}; try { svg.setPointerCapture(e.pointerId); } catch (x) {}
    const dd = down; dd.lt = setTimeout(() => { if (down === dd && !dd.moved) { dd.long = true; feed(); } }, 700);
    e.preventDefault();
  });
  svg.addEventListener('pointermove', e => {
    if (!down) return;
    if (!down.moved && Math.hypot(e.clientX - down.x, e.clientY - down.y) > 7) {
      clearTimeout(down.lt); down.moved = true; dragging = true; cancel(); asleep = false; el.classList.add('drag'); loc = 'air'; perchEl = null;
      setPose('drag'); setMood('scared'); sfx('eek'); say(pick(['Eeek! Put me down! 🙈','Aaah! Too high! Too high! 😱','Let go, let go, LET GO! 🙀']), 4000);
    }
    if (down.moved) {
      face = 1; P.s = 1; P.x = clamp(e.clientX, W / 2, innerWidth - W / 2); P.y = clamp(e.clientY + H - 10, H, innerHeight + 40);
      rot = clamp((e.clientX - down.x) * .12, -22, 22) * .5; render();
    }
  });
  const upFn = e => {
    if (!down) return;
    const d = down; down = null; clearTimeout(d.lt);
    try { svg.releasePointerCapture(d.id); } catch (x) {}
    if (!d.moved) {
      if (d.long) return;
      if (clickT) { clearTimeout(clickT); clickT = 0; flip(); return; }
      clickT = setTimeout(() => { clickT = 0; openB.click(); }, 280); return;
    }
    dragging = false; el.classList.remove('drag'); drop();
  };
  svg.addEventListener('pointerup', upFn); svg.addEventListener('pointercancel', upFn);
  async function drop() {
    cancel(); const y0 = P.y, x0 = P.x, gy = ground();
    sfx('fall'); setMood('cry'); setPose('leap'); say(pick(['Waaaah! 😭','Aaaaah! Waaah! 😭']), 2200);
    const fall = Math.max(420, Math.sqrt(Math.max(0, gy - y0)) * 46);
    let ok = await tween(fall, k => { P.y = y0 + (gy - y0) * k * k; rot = (1 - k) * rot; render(); });
    if (ok) {
      rot = 0; setPose(''); setFx('land', 320); loc = 'ground'; render();
      sfx('thud'); setTimeout(() => sfx('cry'), 250); setMood('cry'); say('Ouch... that hurt! 😭', 1500); await sleep(1500);
      if (!ok || dragging) return;
      sfx('grunt'); setMood('angry'); say(pick(['Hmph! Who did that?! 😤','That was NOT funny! 😠','Grrr... I\u2019m going back! 😡']), 2800); await sleep(1700);
      if (dragging) return;
      await park();
      if (!dragging) setMood('');
    }
  }
  svg.addEventListener('pointerenter', () => {
    if (dragging || chatOpen) return;
    clearTimeout(hoverT); hoverT = setTimeout(() => { if (!dragging && !chatOpen) { say(pick(['Psst — click me if you need help 🍌', 'Tickle tickle! 😆', 'Drag me around if you like', 'Need a hand? Click me!']), 3200); setMood('happy', 2500); } }, 1300);
  });
  svg.addEventListener('pointerleave', () => clearTimeout(hoverT));

  /* ================= chat panel placement ================= */
  function placePanel() {
    const p = panel;
    if (small()) { p.style.left = p.style.top = p.style.right = p.style.bottom = p.style.height = ''; return; }
    const pw = Math.min(396, innerWidth - 24), ph = Math.min(620, innerHeight - 128);
    let left, top, org;
    const roomR = innerWidth - (P.x + W / 2 + 10) - 12, roomL = P.x - W / 2 - 10 - 12;
    if (roomL >= pw) { left = P.x - W / 2 - 10 - pw; org = '100% 100%'; top = clamp(P.y - ph + 10, 12, innerHeight - ph - 12); }
    else if (roomR >= pw) { left = P.x + W / 2 + 10; org = '0% 100%'; top = clamp(P.y - ph + 10, 12, innerHeight - ph - 12); }
    else { left = clamp(P.x - pw / 2, 12, innerWidth - pw - 12); top = clamp(P.y - H - 10 - ph, 12, innerHeight - ph - 12); org = '50% 100%'; }
    p.style.left = left + 'px'; p.style.top = top + 'px'; p.style.right = 'auto'; p.style.bottom = 'auto'; p.style.height = ph + 'px'; p.style.transformOrigin = org;
  }
  new MutationObserver(() => {
    const open = !panel.hidden; if (open === chatOpen) return;
    chatOpen = open;
    if (open) {
      cancel(); asleep = false; hideBub(); setPose(loc === 'edge' ? 'cling' : pose === 'sit' ? 'sit' : ''); setMood('happy', 2500);
      face = (P.x < innerWidth / 2) ? -1 : 1; if (loc !== 'edge') { face = P.x < innerWidth / 2 ? -1 : 1; } render();
      placePanel(); el.classList.add('hide');
    } else {
      el.classList.remove('hide'); lastAct = Date.now(); setMood('', 0); if (!rest) setPose(loc === 'edge' ? 'cling' : '');
      if (rest) setPose('sit');
    }
  }).observe(panel, {attributes: true, attributeFilter: ['hidden']});
  // bot mood -> monkey face; bot speaking -> monkey talks
  const head = g('mbHead');
  if (head) new MutationObserver(() => {
    const m = (head.getAttribute('class') || '').replace('mbx', '').trim();
    if (chatOpen) setMood(m === 'think' ? 'think' : m, m === 'think' ? 0 : 6500);
  }).observe(head, {attributes: true, attributeFilter: ['class']});
  const msgsEl = g('mbMsgs');
  if (msgsEl) new MutationObserver(ms => {
    ms.forEach(m => m.addedNodes.forEach(n => {
      if (n.nodeType === 1 && n.classList.contains('mbr') && !n.classList.contains('me') && !n.querySelector('.mbty')) {
        if (!chatOpen) return; const old = pose; setPose(pose === 'sit' ? 'sit talk' : 'talk'); setTimeout(() => { if (chatOpen) setPose(old); }, 1500);
      }
    }));
  }).observe(msgsEl, {childList: true});

  /* ================= reacting to what the user does ================= */
  const toastEl = g('toast'), saves = []; let lastStreak = 0;
  if (toastEl) new MutationObserver(() => {
    if (toastEl.hidden || !toastEl.textContent) return;
    const t = toastEl.textContent;
    if (/not saved|fail|error|could not|couldn.t|wrong|unable|denied/i.test(t)) { setMood('cry', 3000); say('Oh no… 😢', 2600); }
    else if (/saved|updated|done|success|added|copied|✓/i.test(t)) {
      setMood('happy', 2600); setFx('cheer', 1600); say(pick(['Yay! Nicely done 🎉', 'Saved! 🍌', 'That’s the way! 👏']), 2600);
      if (window.__fx) window.__fx.saved();
      if (/saved to bitrix/i.test(t)) { const n = Date.now(); saves.push(n); while (saves.length && n - saves[0] > 180000) saves.shift(); if (saves.length >= 3 && n - lastStreak > 180000) { lastStreak = n; setTimeout(() => { say('Hat-trick! Three saves in a row 🍌🎉', 3600); setFx('cheer', 2600); fxb(P.x, P.y - H * .7, 26, ['🍌', '🎉', '🎊', '✨'], 1.3); }, 900); } }
    }
  }).observe(toastEl, {attributes: true, attributeFilter: ['hidden']});
  let lastRoute = 0;
  addEventListener('hashchange', () => {
    const h = location.hash, now = Date.now(); if (now - lastRoute < 6000 || chatOpen || dragging) return; lastRoute = now;
    if (/f=(stale|notcontacted|paydue|docpend)/.test(h)) { setMood('conf', 3500); say(pick(['Oh dear, these need chasing! 😟', 'Lots to follow up… I’ll cheer you on 📣']), 3400); }
    else if (/#\/reports/.test(h)) { setMood('happy', 2500); say('Ooh, reports! 📊', 2600); }
  });

  /* ================= watchdog ================= */
  let wasBusy = false, wasHidden = false;
  setInterval(() => {
    const b = uiBusy(); if (b !== wasBusy) { wasBusy = b; if (b) { cancel(); hideBub(); if (loc !== 'ground' || !nearHome()) park(); } }
    if (D.hidden !== wasHidden) { wasHidden = D.hidden; el.classList.toggle('paused', D.hidden); }
    if (loc === 'perch' && perchEl && !dragging) {
      const r = perchEl.getBoundingClientRect();
      if (!perchEl.isConnected || Math.abs(r.top - perchTop) > 4 || r.bottom < 0 || r.top > innerHeight) { cancel(); say('Whoa! The ground moved! 🙈', 1800); toGround(); }
    }
  }, 500);
  addEventListener('resize', () => {
    size(); P.x = clamp(P.x, W / 2, innerWidth - W / 2); if (loc === 'ground') { P.y = ground(); if (!dragging && !chatOpen) P.x = innerWidth - (small() ? 56 : 82); } P.y = Math.min(P.y, ground()); render();
    if (chatOpen) placePanel(); blockCache = null;
  });

  /* ================= rest switch ================= */
  function paintMode() {
    const m = g('monkeyMode'); if (m) m.textContent = rest || reduce ? 'Pranav: resting 😴 (tap to wake)' : 'Pranav: playing (tap to rest)';
  }
  function setRest(v) {
    rest = !!v; try { localStorage.setItem(LS, rest ? '1' : '0'); } catch (e) {}
    paintMode(); cancel(); asleep = false;
    if (rest) { park(); say('Okay, I’ll rest here 😴', 2400); }
    else { setPose(''); say('Yay, jungle time! 🌴', 2400); }
  }
  new MutationObserver(paintMode).observe(D.body, {childList: true, subtree: false});

  /* ================= welcome cinematic ================= */
  function firstName() {
    let n = ''; try { n = String((typeof PROFILE !== 'undefined' && PROFILE.name) || (typeof ME !== 'undefined' && ME) || '').trim(); } catch (e) {}
    n = n.split(/[_\s.\-0-9]+/)[0] || 'there'; return n.charAt(0).toUpperCase() + n.slice(1);
  }
  const WCSS = '' +
    '#ppwel{--hm:clamp(150px,28vh,240px);--r:0px;color:#fff;font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;overflow:hidden;cursor:pointer;user-select:none;-webkit-user-select:none;background:linear-gradient(180deg,#0d2826 0,#1a5551 38%,#9b663e 74%,#f59e6b 100%)!important}' +
    '#ppwel *{box-sizing:border-box}' +
    '.ppw-star{position:absolute;width:3px;height:3px;border-radius:50%;background:#fff;opacity:.3;animation:ppwTw 2.4s ease-in-out infinite}@keyframes ppwTw{0%,100%{opacity:.2}50%{opacity:1}}' +
    '.ppw-sun{position:absolute;left:50%;bottom:16vh;width:min(70vw,640px);aspect-ratio:1;margin-left:calc(min(70vw,640px)/-2);border-radius:50%;background:radial-gradient(circle,rgba(255,226,150,.95) 0,rgba(255,170,110,.55) 35%,transparent 68%);opacity:0;animation:ppwFade 1.4s .9s ease forwards}@keyframes ppwFade{to{opacity:1}}' +
    '.ppw-scene{position:absolute;left:0;right:0;bottom:0;height:62vh;transform-origin:50% 100%;animation:ppwPush 2.6s .5s cubic-bezier(.2,.7,.2,1) both}@keyframes ppwPush{from{transform:scale(1.22)}to{transform:scale(1)}}' +
    '.ppw-scene svg{position:absolute;left:50%;bottom:0;height:100%;min-width:100%;transform:translateX(-50%)}' +
    '.ppw-gates{position:absolute;inset:0;perspective:1500px;z-index:6;pointer-events:none}' +
    '.ppw-gate{position:absolute;top:0;bottom:0;width:50.4%;background:linear-gradient(90deg,#0a211f,#113331 60%,#0a211f);border:0 solid #d4a73a;box-shadow:inset 0 0 0 10px #d4a73a,inset 0 0 0 14px #0a211f,inset 0 0 60px rgba(0,0,0,.6);overflow:hidden}' +
    '.ppw-gate::before{content:"";position:absolute;inset:14px;background:repeating-linear-gradient(90deg,transparent 0 34px,rgba(212,167,58,.9) 34px 40px)}' +
    '.ppw-gate::after{content:"";position:absolute;left:14px;right:14px;top:34%;height:10px;background:#d4a73a;box-shadow:0 calc(30vh) 0 #d4a73a,0 -22vh 0 #d4a73a}' +
    '.ppw-gate.l{left:0;transform-origin:0 50%;animation:ppwOpenL 1.7s .55s cubic-bezier(.55,0,.25,1) forwards}.ppw-gate.r{right:0;transform-origin:100% 50%;animation:ppwOpenR 1.7s .55s cubic-bezier(.55,0,.25,1) forwards}' +
    '@keyframes ppwOpenL{to{transform:rotateY(-104deg);filter:brightness(.55)}}@keyframes ppwOpenR{to{transform:rotateY(104deg);filter:brightness(.55)}}' +
    '.ppw-emb{position:absolute;top:50%;width:clamp(56px,8vw,96px);aspect-ratio:1;margin-top:calc(clamp(56px,8vw,96px)/-2);border-radius:50%;background:radial-gradient(circle at 35% 30%,#fde68a,#d4a73a 60%,#92651a);box-shadow:0 0 0 6px #0a211f,0 6px 20px rgba(0,0,0,.5);display:grid;place-items:center;font:900 clamp(26px,4vw,48px)/1 system-ui;color:#163e3b;z-index:2}.ppw-gate.l .ppw-emb{right:-1px;margin-right:calc(clamp(56px,8vw,96px)/-2)}.ppw-gate.r .ppw-emb{left:-1px;margin-left:calc(clamp(56px,8vw,96px)/-2)}' +
    '.ppw-gates .ppw-emb{display:none}' +
    '.ppw-seam{position:absolute;left:50%;top:50%;width:clamp(64px,9vw,104px);aspect-ratio:1;margin:calc(clamp(64px,9vw,104px)/-2) 0 0 calc(clamp(64px,9vw,104px)/-2);border-radius:50%;background:radial-gradient(circle at 35% 30%,#fde68a,#d4a73a 60%,#92651a);box-shadow:0 0 0 6px #0a211f,0 6px 24px rgba(0,0,0,.55);display:grid;place-items:center;font:900 clamp(28px,4.4vw,52px)/1 system-ui;color:#163e3b;z-index:7;animation:ppwSeam .5s 1.2s ease forwards}@keyframes ppwSeam{to{opacity:0;transform:scale(.4)}}' +
    '.ppw-plaque{position:absolute;left:50%;top:7vh;transform:translateX(-50%);z-index:8;padding:12px 30px;border-radius:14px;background:linear-gradient(180deg,#163e3b,#0d2826);border:3px solid #d4a73a;box-shadow:0 10px 40px rgba(0,0,0,.5),0 0 40px rgba(212,167,58,.35);color:#fde68a;font-weight:900;letter-spacing:.2em;font-size:clamp(14px,2.4vw,26px);white-space:nowrap;animation:ppwPlaque .7s 1.5s ease forwards}@keyframes ppwPlaque{to{opacity:0;transform:translate(-50%,-30px)}}' +
    '.ppw-txt{position:absolute;left:0;right:0;top:6vh;text-align:center;padding:0 18px;z-index:5;pointer-events:none}' +
    '.ppw-txt small{display:block;font-size:clamp(13px,2.2vw,22px);font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#fde68a;opacity:0;animation:ppwUp .7s 2.5s both}' +
    '.ppw-txt h2{margin:4px 0 0;font-size:clamp(36px,7.6vw,86px);line-height:1.02;font-weight:900;letter-spacing:-.03em;text-shadow:0 6px 30px rgba(0,0,0,.4),0 0 40px rgba(250,204,21,.25)}' +
    '.ppw-txt h2 span{display:inline-block;opacity:0;animation:ppwLetter .55s both cubic-bezier(.2,1.7,.4,1)}.ppw-txt h2 .sp{width:.28em}.ppw-txt h2 .y{color:#fcd34d}' +
    '.ppw-txt p{margin:12px auto 0;max-width:720px;font-size:clamp(14px,2vw,20px);font-weight:600;color:#fff9f4;opacity:0;animation:ppwUp .7s 3.7s both;text-shadow:0 2px 12px rgba(0,0,0,.4)}' +
    '@keyframes ppwUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}@keyframes ppwLetter{from{opacity:0;transform:translateY(40px) scale(.4) rotate(-14deg)}to{opacity:1;transform:none}}' +
    '.ppw-unis{position:absolute;left:0;right:0;top:calc(6vh + clamp(150px,27vh,260px));display:flex;flex-wrap:wrap;justify-content:center;gap:12px;padding:0 4vw;z-index:4;pointer-events:none}' +
    '.ppw-uc{display:flex;align-items:center;gap:10px;padding:10px 16px 10px 12px;border-radius:16px;background:rgba(255,255,255,.94);color:#132e2c;box-shadow:0 14px 34px rgba(10,44,42,.35);min-width:150px;opacity:0;transform:translateY(40px) scale(.6);animation:ppwCard .6s cubic-bezier(.2,1.5,.4,1) forwards}' +
    '.ppw-uc i{font-style:normal;display:grid;place-items:center;width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#ebfcfb,#fbcfe8);font-size:20px}.ppw-uc b{display:block;font-size:20px;line-height:1.1;letter-spacing:-.3px}.ppw-uc small{display:block;font-size:11.5px;font-weight:700;color:#4e6564;max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}' +
    '.ppw-uc.all{background:linear-gradient(135deg,#fde68a,#fbbf24);color:#3b2400}.ppw-uc.all i{background:rgba(255,255,255,.55)}.ppw-uc.all small{color:#7c4a03}' +
    '@keyframes ppwCard{to{opacity:1;transform:none}}' +
    '.ppw-mk{position:absolute;left:50%;bottom:11vh;width:calc(var(--hm)*.78);height:var(--hm);margin-left:calc(var(--hm)*-.39);z-index:3;transform-origin:50% 100%;animation:ppwRun 1.5s 1.9s cubic-bezier(.3,.6,.3,1) both}' +
    '@keyframes ppwRun{0%{opacity:0;transform:translateY(-16vh) scale(.2)}12%{opacity:1}100%{opacity:1;transform:none}}' +
    '.ppw-mk .mkr{display:block;width:100%;height:100%;overflow:visible;filter:drop-shadow(0 10px 10px rgba(8,38,36,.4))}' +
    '.ppw-mk .bob{width:100%;height:100%;animation:ppwBob .3s 1.9s ease-in-out 5 alternate both}@keyframes ppwBob{from{transform:translateY(0)}to{transform:translateY(-3.5vh)}}' +
    '.ppw-gr{position:absolute;left:-5%;right:-5%;bottom:0;height:13vh;background:radial-gradient(120% 100% at 50% 0,#2f8f4e 0,#14532d 60%,#052e16 100%);border-radius:50% 50% 0 0/22px 22px 0 0;z-index:2}' +
    '.ppw-snd{position:absolute;left:16px;bottom:16px;z-index:9;padding:8px 14px;border-radius:999px;border:1px solid rgba(255,255,255,.35);background:rgba(255,255,255,.14);color:#fff;font-weight:700;font-size:13px;cursor:pointer;animation:ppwTw 1.6s ease-in-out infinite}' +
    '.ppw-skip{position:absolute;right:16px;bottom:16px;z-index:9;border:1px solid rgba(255,255,255,.35);background:rgba(255,255,255,.14);color:#fff;border-radius:999px;padding:8px 16px;font:600 13px system-ui;cursor:pointer;backdrop-filter:blur(6px);opacity:0;animation:ppwUp .5s .8s both}.ppw-skip:hover{background:rgba(255,255,255,.28)}' +
    '.mkr .gcap{display:none}.mkr.grad .gcap{display:block}' +
    '@media(prefers-reduced-motion:reduce){#ppwel *{animation:none!important}.ppw-txt small,.ppw-txt h2 span,.ppw-txt p,.ppw-skip,.ppw-uc{opacity:1!important;transform:none!important}.ppw-gates,.ppw-plaque,.ppw-seam{display:none}.ppw-sun{opacity:1}}';

  function campusSVG() {
    return '<svg viewBox="0 0 800 400" preserveAspectRatio="xMidYMax meet" aria-hidden="true"><defs><linearGradient id="cwW" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#fbf6ea"/><stop offset="1" stop-color="#e2d9c5"/></linearGradient><linearGradient id="cwWs" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#bdb5a2"/><stop offset="1" stop-color="#d4ccb8"/></linearGradient><linearGradient id="cwC" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#cfc7b3"/><stop offset=".28" stop-color="#fffdf7"/><stop offset=".62" stop-color="#eee8da"/><stop offset="1" stop-color="#aaa28e"/></linearGradient><linearGradient id="cwR" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#37c9ba"/><stop offset="1" stop-color="#14796f"/></linearGradient><linearGradient id="cwGl" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#fff0b8"/><stop offset="1" stop-color="#ffb23e"/></linearGradient><linearGradient id="cwGc" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#a6e9e3"/><stop offset="1" stop-color="#3f9c98"/></linearGradient><linearGradient id="cwD" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#b8821f"/><stop offset=".35" stop-color="#ffe58f"/><stop offset=".7" stop-color="#e0a62a"/><stop offset="1" stop-color="#8f5f12"/></linearGradient><linearGradient id="cwS" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#f7f0de"/><stop offset="1" stop-color="#cdbf9f"/></linearGradient><linearGradient id="cwH" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#3a9b58"/><stop offset="1" stop-color="#14532d"/></linearGradient><linearGradient id="cwP" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#f2d9a6"/><stop offset="1" stop-color="#d6b274"/></linearGradient><radialGradient id="cwT" cx=".4" cy=".35" r=".75"><stop offset="0" stop-color="#2f9e55"/><stop offset="1" stop-color="#0f4a27"/></radialGradient><radialGradient id="cwLg"><stop offset="0" stop-color="#ffe9a0" stop-opacity=".85"/><stop offset="1" stop-color="#ffe9a0" stop-opacity="0"/></radialGradient><radialGradient id="cwDoor" cx=".5" cy=".8" r=".9"><stop offset="0" stop-color="#ffe9a8"/><stop offset="1" stop-color="#e58b1f"/></radialGradient><linearGradient id="cwSh" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#000" stop-opacity=".0"/><stop offset="1" stop-color="#000" stop-opacity=".35"/></linearGradient></defs><path d="M-2400 336C-1800 300 -900 304 -300 306C-120 290 220 300 330 322C470 300 620 286 800 326C1100 300 1900 306 3200 336V420H-2400z" fill="#1b4e4b" opacity=".55"/><ellipse cx="400" cy="314" rx="380" ry="10" fill="#000" opacity=".28"/><rect x="364" y="84" width="72" height="62" fill="url(#cwW)"/><rect x="358" y="80" width="84" height="7" rx="2" fill="#d9d0ba"/><path d="M374 122V102.0a6.0 6.0 0 0 1 12 0V122Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M380.0 97V122M374 110.3H386" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="372" y="122" width="16" height="3.5" rx="1" fill="#d8cfb9"/><path d="M394 122V102.0a6.0 6.0 0 0 1 12 0V122Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M400.0 97V122M394 110.3H406" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="392" y="122" width="16" height="3.5" rx="1" fill="#d8cfb9"/><path d="M414 122V102.0a6.0 6.0 0 0 1 12 0V122Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M420.0 97V122M414 110.3H426" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="412" y="122" width="16" height="3.5" rx="1" fill="#d8cfb9"/><path d="M356 82Q356 20 400 14Q444 20 444 82Z" fill="url(#cwD)"/><path d="M400 14Q384 40 380 82M400 14Q416 40 420 82M400 14V82" stroke="#fff3c2" stroke-width="1.6" opacity=".55" fill="none"/><rect x="399" y="0" width="3" height="16" fill="#c99a2a"/><path d="M402 2h22l-7 6 7 6h-22z" fill="#ef4444"/><path d="M246 180L400 114L554 180Z" fill="url(#cwW)"/><path d="M268 176L400 124L532 176Z" fill="#e9e2d0"/><path d="M246 180L400 114L554 180" fill="none" stroke="url(#cwR)" stroke-width="7" stroke-linejoin="round"/><circle cx="400" cy="156" r="13" fill="#fff" stroke="#17b3a6" stroke-width="4"/><path d="M400 148v8l6 4" stroke="#2a3b4a" stroke-width="2" fill="none" stroke-linecap="round"/><rect x="250" y="178" width="300" height="8" fill="#d9d0ba"/><rect x="256" y="186" width="288" height="12" fill="url(#cwW)"/><rect x="256" y="196" width="288" height="3" fill="#b9b09a"/><rect x="262" y="187" width="5" height="8" fill="#cfc6af"/><rect x="274" y="187" width="5" height="8" fill="#cfc6af"/><rect x="286" y="187" width="5" height="8" fill="#cfc6af"/><rect x="298" y="187" width="5" height="8" fill="#cfc6af"/><rect x="310" y="187" width="5" height="8" fill="#cfc6af"/><rect x="322" y="187" width="5" height="8" fill="#cfc6af"/><rect x="334" y="187" width="5" height="8" fill="#cfc6af"/><rect x="346" y="187" width="5" height="8" fill="#cfc6af"/><rect x="358" y="187" width="5" height="8" fill="#cfc6af"/><rect x="370" y="187" width="5" height="8" fill="#cfc6af"/><rect x="382" y="187" width="5" height="8" fill="#cfc6af"/><rect x="394" y="187" width="5" height="8" fill="#cfc6af"/><rect x="406" y="187" width="5" height="8" fill="#cfc6af"/><rect x="418" y="187" width="5" height="8" fill="#cfc6af"/><rect x="430" y="187" width="5" height="8" fill="#cfc6af"/><rect x="442" y="187" width="5" height="8" fill="#cfc6af"/><rect x="454" y="187" width="5" height="8" fill="#cfc6af"/><rect x="466" y="187" width="5" height="8" fill="#cfc6af"/><rect x="478" y="187" width="5" height="8" fill="#cfc6af"/><rect x="490" y="187" width="5" height="8" fill="#cfc6af"/><rect x="502" y="187" width="5" height="8" fill="#cfc6af"/><rect x="514" y="187" width="5" height="8" fill="#cfc6af"/><rect x="526" y="187" width="5" height="8" fill="#cfc6af"/><rect x="538" y="187" width="5" height="8" fill="#cfc6af"/><rect x="262" y="199" width="276" height="113" fill="url(#cwWs)"/><path d="M378 312V258a22 22 0 0 1 44 0V312Z" fill="url(#cwDoor)" stroke="#6d5a3a" stroke-width="3"/><path d="M400 236V312M378 270H422" stroke="#6d5a3a" stroke-width="1.5" opacity=".7"/><path d="M280 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M287.0 223V256M280 240.7H294" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="278" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><path d="M312 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M319.0 223V256M312 240.7H326" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="310" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><path d="M344 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M351.0 223V256M344 240.7H358" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="342" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><path d="M440 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M447.0 223V256M440 240.7H454" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="438" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><path d="M472 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M479.0 223V256M472 240.7H486" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="470" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><path d="M504 256V229.0a7.0 7.0 0 0 1 14 0V256Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M511.0 223V256M504 240.7H518" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="502" y="256" width="18" height="3.5" rx="1" fill="#d8cfb9"/><rect x="259.0" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="262.0" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="259.0" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M267.0 208V302M273.0 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="295.3" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="298.3" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="295.3" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M303.3 208V302M309.3 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="331.6" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="334.6" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="331.6" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M339.6 208V302M345.6 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="367.9" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="370.9" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="367.9" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M375.9 208V302M381.9 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="404.2" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="407.2" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="404.2" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M412.2 208V302M418.2 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="440.5" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="443.5" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="440.5" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M448.5 208V302M454.5 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="476.79999999999995" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="479.79999999999995" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="476.79999999999995" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M484.79999999999995 208V302M490.79999999999995 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="513.0999999999999" y="199" width="22" height="7" rx="1.5" fill="#d9d0ba"/><rect x="516.0999999999999" y="206" width="16" height="98" fill="url(#cwC)"/><rect x="513.0999999999999" y="304" width="22" height="8" rx="1.5" fill="#cfc6af"/><path d="M521.0999999999999 208V302M527.0999999999999 208V302" stroke="#b5ad98" stroke-width="1" opacity=".7"/><rect x="258" y="312" width="284" height="7" fill="url(#cwS)"/><rect x="246" y="319" width="308" height="7" fill="url(#cwS)"/><rect x="234" y="326" width="332" height="8" fill="url(#cwS)"/><rect x="234" y="334" width="332" height="3" fill="#000" opacity=".18"/><path d="M334 337h132l60 63H274z" fill="url(#cwP)"/><g><rect x="40" y="206" width="224" height="106" fill="url(#cwW)"/><rect x="40" y="256" width="224" height="3" fill="#cdc4ad"/><rect x="40" y="203" width="224" height="3" fill="#d9d0ba"/><path d="M32 206h240l-12-20H44z" fill="url(#cwR)"/><path d="M44 186h216" stroke="#7ff0e2" stroke-width="1.6" opacity=".6"/><path d="M32 206h240" stroke="#0f5e57" stroke-width="2.5"/><path d="M62 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M71.0 215V246M62 231.6H80" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="60" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M62 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M71.0 269V298M62 284.5H80" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="60" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M100 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M109.0 215V246M100 231.6H118" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="98" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M100 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M109.0 269V298M100 284.5H118" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="98" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M138 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M147.0 215V246M138 231.6H156" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="136" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M138 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M147.0 269V298M138 284.5H156" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="136" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M176 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M185.0 215V246M176 231.6H194" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="174" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M176 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M185.0 269V298M176 284.5H194" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="174" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M214 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M223.0 215V246M214 231.6H232" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="212" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M214 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M223.0 269V298M214 284.5H232" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="212" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><rect x="40" y="206" width="18" height="106" fill="url(#cwW)" opacity=".9"/><path d="M58 206V312" stroke="#b9b09a" stroke-width="1.5"/><rect x="40" y="210" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="225" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="240" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="255" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="270" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="285" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="300" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="206" width="18" height="106" fill="url(#cwW)" opacity=".9"/><path d="M264 206V312" stroke="#b9b09a" stroke-width="1.5"/><rect x="246" y="210" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="225" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="240" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="255" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="270" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="285" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="300" width="18" height="1.4" fill="#c7bea8"/><rect x="46" y="300" width="206" height="14" rx="7" fill="url(#cwH)"/><circle cx="54.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="68.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="83.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="97.5" cy="306" r="2.1" fill="#ff6b8a"/><circle cx="112.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="126.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="141.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="155.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="170.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="184.5" cy="306" r="2.1" fill="#ff6b8a"/><circle cx="199.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="213.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="228.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="242.5" cy="306" r="2.1" fill="#ffb347"/></g><g transform="translate(800 0) scale(-1 1)"><rect x="40" y="206" width="224" height="106" fill="url(#cwW)"/><rect x="40" y="256" width="224" height="3" fill="#cdc4ad"/><rect x="40" y="203" width="224" height="3" fill="#d9d0ba"/><path d="M32 206h240l-12-20H44z" fill="url(#cwR)"/><path d="M44 186h216" stroke="#7ff0e2" stroke-width="1.6" opacity=".6"/><path d="M32 206h240" stroke="#0f5e57" stroke-width="2.5"/><path d="M62 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M71.0 215V246M62 231.6H80" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="60" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M62 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M71.0 269V298M62 284.5H80" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="60" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M100 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M109.0 215V246M100 231.6H118" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="98" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M100 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M109.0 269V298M100 284.5H118" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="98" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M138 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M147.0 215V246M138 231.6H156" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="136" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M138 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M147.0 269V298M138 284.5H156" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="136" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M176 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M185.0 215V246M176 231.6H194" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="174" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M176 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M185.0 269V298M176 284.5H194" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="174" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M214 246V223.0a9.0 9.0 0 0 1 18 0V246Z" fill="url(#cwGl)" stroke="#8a826c" stroke-width="1.6"/><path d="M223.0 215V246M214 231.6H232" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="212" y="246" width="22" height="3.5" rx="1" fill="#d8cfb9"/><path d="M214 298V277.0a9.0 9.0 0 0 1 18 0V298Z" fill="url(#cwGc)" stroke="#8a826c" stroke-width="1.6"/><path d="M223.0 269V298M214 284.5H232" stroke="#8a826c" stroke-width="1" opacity=".7"/><rect x="212" y="298" width="22" height="3.5" rx="1" fill="#d8cfb9"/><rect x="40" y="206" width="18" height="106" fill="url(#cwW)" opacity=".9"/><path d="M58 206V312" stroke="#b9b09a" stroke-width="1.5"/><rect x="40" y="210" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="225" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="240" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="255" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="270" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="285" width="18" height="1.4" fill="#c7bea8"/><rect x="40" y="300" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="206" width="18" height="106" fill="url(#cwW)" opacity=".9"/><path d="M264 206V312" stroke="#b9b09a" stroke-width="1.5"/><rect x="246" y="210" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="225" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="240" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="255" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="270" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="285" width="18" height="1.4" fill="#c7bea8"/><rect x="246" y="300" width="18" height="1.4" fill="#c7bea8"/><rect x="46" y="300" width="206" height="14" rx="7" fill="url(#cwH)"/><circle cx="54.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="68.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="83.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="97.5" cy="306" r="2.1" fill="#ff6b8a"/><circle cx="112.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="126.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="141.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="155.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="170.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="184.5" cy="306" r="2.1" fill="#ff6b8a"/><circle cx="199.0" cy="303" r="2.1" fill="#ffb347"/><circle cx="213.5" cy="306" r="2.1" fill="#ffb347"/><circle cx="228.0" cy="303" r="2.1" fill="#ff6b8a"/><circle cx="242.5" cy="306" r="2.1" fill="#ffb347"/></g><circle cx="222" cy="288" r="30" fill="url(#cwLg)"/><rect x="220.5" y="292" width="3" height="42" fill="#2a3b4a"/><rect x="217" y="282" width="10" height="12" rx="3" fill="#ffe08a" stroke="#2a3b4a" stroke-width="1.5"/><path d="M215 282h14l-7-7z" fill="#2a3b4a"/><circle cx="578" cy="288" r="30" fill="url(#cwLg)"/><rect x="576.5" y="292" width="3" height="42" fill="#2a3b4a"/><rect x="573" y="282" width="10" height="12" rx="3" fill="#ffe08a" stroke="#2a3b4a" stroke-width="1.5"/><path d="M571 282h14l-7-7z" fill="#2a3b4a"/><circle cx="40" cy="312" r="36" fill="url(#cwT)"/><circle cx="29.200000000000003" cy="299.4" r="19.8" fill="#3fb866" opacity=".35"/><rect x="37" y="344" width="6" height="10" fill="#4a2f1b"/><circle cx="96" cy="324" r="26" fill="url(#cwT)"/><circle cx="88.2" cy="314.9" r="14.3" fill="#3fb866" opacity=".35"/><rect x="93" y="346" width="6" height="10" fill="#4a2f1b"/><circle cx="760" cy="312" r="36" fill="url(#cwT)"/><circle cx="749.2" cy="299.4" r="19.8" fill="#3fb866" opacity=".35"/><rect x="757" y="344" width="6" height="10" fill="#4a2f1b"/><circle cx="704" cy="324" r="26" fill="url(#cwT)"/><circle cx="696.2" cy="314.9" r="14.3" fill="#3fb866" opacity=".35"/><rect x="701" y="346" width="6" height="10" fill="#4a2f1b"/></svg>';
  }

  function uniCards() {
    try {
      const m = {}; allRows.forEach(r => { const u = uniOf(r); m[u] = (m[u] || 0) + 1; });
      const list = Object.keys(m).filter(k => k && !/^\(?\s*(no university|unassigned|unknown)|^[—–-]+$/i.test(k)).map(k => ({n: k, c: m[k]})).sort((a, b) => b.c - a.c).slice(0, 5);
      return {total: allRows.length, list: list};
    } catch (e) { return null; }
  }

  function welcome() {
    const ov = g('ppwel');
    if (!ov) return Promise.resolve(null);
    welcomeOn = true;
    const S = D.createElement('style'); S.textContent = WCSS; D.head.appendChild(S);
    const nm = firstName();
    let letters = '', idx = 0;
    [['Pranav', ''], ['Pipeline', 'y']].forEach((w, wi) => { for (const ch of w[0]) { letters += '<span class="' + w[1] + '" style="animation-delay:' + (2.8 + idx * .055).toFixed(2) + 's">' + ch + '</span>'; idx++; } if (!wi) letters += '<span class="sp" aria-hidden="true" style="animation-delay:0s"> </span>'; });
    let stars = ''; for (let i = 0; i < 40; i++) stars += '<i class="ppw-star" style="left:' + rand(1, 99).toFixed(0) + '%;top:' + rand(1, 55).toFixed(0) + '%;animation-delay:' + (-rand(0, 2.4)).toFixed(1) + 's;width:' + (rand(2, 4)).toFixed(0) + 'px;height:' + (rand(2, 4)).toFixed(0) + 'px"></i>';
    ov.innerHTML =
      stars + '<div class="ppw-sun"></div>' +
      '<div class="ppw-scene">' + campusSVG() + '</div><div class="ppw-gr"></div>' +
      '<div class="ppw-txt"><small>Welcome to</small><h2 aria-label="Pranav Pipeline">' + letters + '</h2><p>Hi ' + nm.replace(/[<>&]/g, '') + '! I’m <b>Pranav</b> — I keep an eye on every university’s deals for you.</p></div>' +
      '<div class="ppw-unis" id="ppwUnis"></div>' +
      '<div class="ppw-mk"><div class="bob">' + rig('grad walk') + '</div></div>' +
      '<div class="ppw-gates"><div class="ppw-gate l"></div><div class="ppw-gate r"></div></div><div class="ppw-seam">P</div><div class="ppw-plaque">PRANAV PIPELINE</div>' +
      '<button type="button" class="ppw-skip" aria-label="Skip the welcome">Skip ›</button>';
    ov.removeAttribute('aria-hidden'); ov.setAttribute('role', 'dialog'); ov.setAttribute('aria-label', 'Welcome to Pranav Pipeline');
    D.body.style.overflow = 'hidden';
    const mk = ov.querySelector('.ppw-mk'), mkr = mk.querySelector('.mkr');
    const timers = []; let cardsDone = false;
    const snd = window.PPSound, hello = 'Hi ' + nm + '! Welcome to Pranav Pipeline.';
    if (snd && snd.on) {
      if (!snd.locked()) {
        timers.push(setTimeout(() => snd.play('gate'), 550)); timers.push(setTimeout(() => snd.play('whoosh'), 1900));
        timers.push(setTimeout(() => { snd.play('chime'); snd.speak(hello, 'excited'); }, 3350));
      } else {
        const hint = D.createElement('div'); hint.className = 'ppw-snd'; hint.textContent = '🔊 Tap for sound'; ov.appendChild(hint);
        hint.addEventListener('click', e => e.stopPropagation());
        snd.later(() => { hint.remove(); snd.play('chime'); snd.speak(hello, 'excited'); });
      }
    }
    timers.push(setTimeout(() => { mkr.setAttribute('class', 'mkr mbx grad wave happy'); }, 3350));
    function countUp(el, to) { const t0 = performance.now(); (function f(now) { const k = Math.min(1, (now - t0) / 1100), e = 1 - Math.pow(1 - k, 3); el.textContent = Math.round(to * e).toLocaleString('en-IN'); if (k < 1) requestAnimationFrame(f); })(t0); }
    function showCards() {
      if (cardsDone) return; const d = uniCards(); if (!d || !d.total) return; cardsDone = true;
      const box = g('ppwUnis'); if (!box) return;
      const items = [{n: 'All universities', c: d.total, all: 1}].concat(d.list);
      box.innerHTML = items.map((u, i) => '<div class="ppw-uc' + (u.all ? ' all' : '') + '" style="animation-delay:' + (i * .18).toFixed(2) + 's"><i>' + (u.all ? '🏛️' : '🎓') + '</i><span><b data-to="' + u.c + '">0</b><small>' + String(u.n).replace(/[<>&]/g, '') + (u.all ? ' · deals' : ' · deals') + '</small></span></div>').join('');
      box.querySelectorAll('b[data-to]').forEach(b => countUp(b, +b.dataset.to));
    }
    const cardT = setInterval(() => { if (performance.now() - t00 > 3300) showCards(); if (cardsDone) clearInterval(cardT); }, 150);
    const t00 = performance.now();
    return new Promise(resolve => {
      let done = false;
      function end() {
        if (done) return; done = true; timers.forEach(clearTimeout); clearInterval(cardT);
        D.removeEventListener('keydown', onKey);
        const r = mk.getBoundingClientRect(), cx = r.left + r.width / 2, cy = r.top + r.height * .55;
        resolve({x: cx, y: r.bottom, h: r.height});
        ov.style.pointerEvents = 'none';
        const m = 'radial-gradient(circle at ' + cx.toFixed(0) + 'px ' + cy.toFixed(0) + 'px, transparent calc(var(--r) - 2px), #000 var(--r))';
        ov.style.webkitMaskImage = m; ov.style.maskImage = m;
        const mx = Math.hypot(Math.max(cx, innerWidth - cx), Math.max(cy, innerHeight - cy)) * 1.08;
        const t0 = performance.now(), dur = reduce ? 1 : 900;
        (function f(now) {
          const k = Math.min(1, (now - t0) / dur), e = k * k * (3 - 2 * k);
          ov.style.setProperty('--r', (mx * e).toFixed(0) + 'px');
          if (k < 1) requestAnimationFrame(f); else { ov.remove(); S.remove(); D.body.style.overflow = ''; welcomeOn = false; }
        })(t0);
      }
      function onKey(e) { if (e.key === 'Escape' || e.key === 'Enter' || e.key === ' ') { e.preventDefault(); end(); } }
      ov.addEventListener('click', end); D.addEventListener('keydown', onKey);
      timers.push(setTimeout(end, reduce ? 1800 : 6200));
    });
  }

  /* ================= start ================= */
  season = seasonNow(); size(); paintMode();
  function setup(from) {
    started = true; render();
    if (reduce) { rest = true; P.x = innerWidth - 82; P.y = ground(); setPose('sit'); render(); return; }
    if (from) {
      P.x = from.x; P.y = Math.min(from.y, ground()); P.s = clamp(from.h / H, 1, 3); face = 1; setPose('wave'); setMood('happy', 3000); render();
      (async () => {
        await sleep(250);
        await leapTo(innerWidth - (small() ? 56 : 82), ground(), 140, 1);
        face = 1; render(); setPose('wave'); setMood('happy', 3200); setFx('cheer', 1400);
        say('Hello hello! 👋 Welcome, ' + firstName() + '!', 4200);
        if (!await sleep(4300)) return; setPose('');
        let day = ''; try { day = localStorage.getItem('pp_day') || ''; localStorage.setItem('pp_day', new Date().toDateString()); } catch (e) {}
        if (day !== new Date().toDateString()) { const h = new Date().getHours(); setPose('dance'); setMood('happy', 3200); say('First login today — ' + (h < 12 ? 'good morning ☀️' : h < 17 ? 'good afternoon 🌤' : 'good evening 🌙') + '!', 3400); fxb(P.x, P.y - H * .7, 18, ['🌞', '🍌', '✨', '🎉'], 1); if (await sleep(3600)) setPose(''); }
      })();
    } else {
      P.x = innerWidth + 70; P.y = ground(); face = 1; render();
      (async () => {
        await sleep(600);
        if (rest) { await goHome(); setPose('sit'); }
        else { await walkTo(innerWidth - (small() ? 56 : 82), 110); setPose('wave'); if (!sessionStorage.getItem('pp_mk_hi')) { try { sessionStorage.setItem('pp_mk_hi', '1'); } catch (e) {} say('Hello hello! 👋', 3000); } await sleep(2200); setPose(''); }
      })();
    }
  }
  setTimeout(() => { try { if (sessionStorage.getItem('pp_ban_hint')) return; sessionStorage.setItem('pp_ban_hint', '1'); } catch (e) {} if (!chatOpen && !dragging && !rest) say('Psst! Got a 🍌 for me? Drag the one at the bottom-left over to me!', 5200); }, 30000);
  /* reacting to the data itself */
  let lastTotal = 0;
  function total() { try { return typeof allRows !== 'undefined' ? allRows.length : 0; } catch (e) { return 0; } }
  function dataReact(first) {
    if (chatOpen || dragging || rest || reduce) return;
    const n = total(); let st = 0; try { st = (prioStats() || {}).stale || 0; } catch (e) {}
    let prev = 0; try { prev = +(localStorage.getItem('pp_last_total') || 0); localStorage.setItem('pp_last_total', String(n)); } catch (e) {}
    const base = first ? prev : lastTotal;
    if (base && n > base) { setMood('happy', 3600); setFx('cheer', 2600); say('🎉 ' + (n - base) + ' new deal' + (n - base === 1 ? '' : 's') + (first ? ' since you were last here!' : ' came in!'), 4200); fxb(P.x, P.y - H * .7, 16, ['🎉', '🍌', '✨'], 1); }
    else if (first && st > 0) { setPose('fan'); say('😓 ' + st + ' follow-up' + (st === 1 ? ' is' : 's are') + ' overdue — let’s chase them!', 4200); sleep(4200).then(ok => { if (ok && pose === 'fan') setPose(''); }); }
    lastTotal = n;
  }
  (function () { let tries = 0; const iv = setInterval(() => { if (total() > 0 && started && !welcomeOn) { clearInterval(iv); lastTotal = total(); setTimeout(() => dataReact(true), window.__ppWelcome ? 12500 : 5500); } else if (++tries > 80) clearInterval(iv); }, 1000); })();
  const upd0 = g('updated');
  if (upd0) new MutationObserver(() => { const n = total(); if (n && lastTotal && n !== lastTotal) dataReact(false); else if (n && !lastTotal) lastTotal = n; }).observe(upd0, {childList: true, characterData: true, subtree: true});
  window.__monkeyLife = {
    toggle: () => setRest(!rest), say: say, welcome: welcome,
    state: () => ({x: P.x, y: P.y, loc: loc, pose: pose, rest: rest, chatOpen: chatOpen, dragging: dragging, asleep: asleep}),
    force: o => { cancel(); if (o.pose != null) setPose(o.pose); if (o.x != null) { P.x = o.x; } if (o.y != null) { P.y = o.y; } render(); },
    act: n => ({perch: perchOn, edge: edgeOn, ground: toGround, roam: roamGround, idle: () => doIdle(false), climb: climbMove, act: doAct})[n](),
    el: el
  };
  if (rest && !reduce) { /* resting from the start */ }
  if (window.__ppWelcome && g('ppwel')) welcome().then(from => { setup(from); }); else setup(null);
  loop();
})();
</script>
<!--/LIFE-->
<!--FX-->
<style id="fxcss">
/* live pulse next to "Updated …" */
#updated::before{content:"";display:inline-block;width:8px;height:8px;border-radius:50%;background:#22c55e;margin-right:7px;vertical-align:middle;box-shadow:0 0 0 0 rgba(34,197,94,.6);animation:fxPulse 2.2s ease-out infinite}
@keyframes fxPulse{0%{box-shadow:0 0 0 0 rgba(34,197,94,.55)}70%,100%{box-shadow:0 0 0 9px rgba(34,197,94,0)}}
/* number flash after a refresh changes a value */
.fxflash{display:inline-block;transform-origin:0 60%;animation:fxFlash 1.3s ease-out}
@keyframes fxFlash{0%{color:#f59e0b;text-shadow:0 0 16px rgba(245,158,11,.8);transform:scale(1.12)}100%{text-shadow:0 0 0 transparent;transform:none}}
/* page transitions */
.fxin{animation:fxIn .5s cubic-bezier(.2,.8,.2,1) both}
@keyframes fxIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
.fxrows tbody tr{animation:fxRow .4s cubic-bezier(.2,.8,.2,1) both}
.fxrows tbody tr:nth-child(1){animation-delay:.02s}.fxrows tbody tr:nth-child(2){animation-delay:.06s}.fxrows tbody tr:nth-child(3){animation-delay:.1s}.fxrows tbody tr:nth-child(4){animation-delay:.14s}.fxrows tbody tr:nth-child(5){animation-delay:.18s}.fxrows tbody tr:nth-child(6){animation-delay:.22s}.fxrows tbody tr:nth-child(7){animation-delay:.26s}.fxrows tbody tr:nth-child(8){animation-delay:.3s}.fxrows tbody tr:nth-child(9){animation-delay:.34s}.fxrows tbody tr:nth-child(n+10){animation-delay:.38s}
@keyframes fxRow{from{opacity:0;transform:translateX(-14px)}to{opacity:1;transform:none}}
/* overdue wiggle */
.fxwig{display:inline-block;animation:fxWig 4.5s ease-in-out infinite;transform-origin:50% 20%}
@keyframes fxWig{0%,70%,100%{transform:none}74%{transform:rotate(-16deg)}78%{transform:rotate(14deg)}82%{transform:rotate(-12deg)}86%{transform:rotate(9deg)}90%{transform:rotate(-5deg)}}
/* edited row glow */
tr.fxhi td{animation:fxHi 2.6s ease-out}
@keyframes fxHi{0%,30%{background:rgba(44,218,206,.32)}100%{background:transparent}}
/* whatsapp pop-up */
.wapop{animation:fxPop .32s cubic-bezier(.3,1.6,.5,1) both!important;transform-origin:20% 0}
@keyframes fxPop{from{opacity:0;transform:scale(.7) translateY(-8px)}to{opacity:1;transform:none}}
.wapop button{animation:fxSlide .35s cubic-bezier(.2,.9,.3,1.2) both}
.wapop button:nth-of-type(1){animation-delay:.08s}.wapop button:nth-of-type(2){animation-delay:.14s}.wapop button:nth-of-type(3){animation-delay:.2s}.wapop button:nth-of-type(4){animation-delay:.26s}
@keyframes fxSlide{from{opacity:0;transform:translateX(-18px)}to{opacity:1;transform:none}}
/* loading pill */
#fxLoad{position:fixed;left:88px;bottom:18px;z-index:45;display:flex;align-items:center;gap:10px;padding:8px 16px 8px 10px;border-radius:999px;background:var(--card);color:var(--text);border:1px solid var(--line);box-shadow:0 12px 30px rgba(29,90,86,.25);font-weight:700;font-size:13px;animation:fxPop .35s cubic-bezier(.3,1.6,.5,1) both}
#fxLoad[hidden]{display:none}
#fxLoad .sw{position:relative;width:34px;height:34px;transform-origin:50% -6px;animation:fxSwing 1.1s ease-in-out infinite alternate}
#fxLoad .sw svg{width:34px;height:34px;overflow:visible}
@keyframes fxSwing{from{transform:rotate(-26deg)}to{transform:rotate(26deg)}}
#fxLoad em{font-style:normal;display:inline-block;animation:fxBounce .7s ease-in-out infinite alternate}
@keyframes fxBounce{from{transform:translateY(0)}to{transform:translateY(-4px)}}
/* success tick */
#fxTick{position:fixed;left:50%;bottom:92px;width:84px;height:84px;margin-left:-42px;z-index:70;pointer-events:none;animation:fxTickIn 1.7s ease both}
#fxTick circle{fill:#16a34a;filter:drop-shadow(0 8px 14px rgba(22,163,74,.45))}
#fxTick path{fill:none;stroke:#fff;stroke-width:6;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:50;stroke-dashoffset:50;animation:fxDraw .5s .25s ease forwards}
@keyframes fxDraw{to{stroke-dashoffset:0}}
@keyframes fxTickIn{0%{opacity:0;transform:scale(.3)}18%{opacity:1;transform:scale(1.08)}28%{transform:scale(1)}80%{opacity:1}100%{opacity:0;transform:translateY(-14px)}}
.fxp{position:fixed;z-index:75;pointer-events:none;font-size:22px;will-change:transform,opacity}
</style>
<script>
(function () {
  'use strict';
  const D = document, B = D.body, $ = id => D.getElementById(id);
  const off = !!(window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches);
  const rand = (a, b) => a + Math.random() * (b - a), pick = a => a[Math.floor(Math.random() * a.length)];

  /* ---------- confetti / emoji burst ---------- */
  function burst(x, y, n, emojis, power) {
    if (off || !D.body.animate) return;
    emojis = emojis || ['🍌', '🎉', '✨', '🍃', '🎊'];
    for (let i = 0; i < (n || 14); i++) {
      const s = D.createElement('span'); s.className = 'fxp'; s.textContent = pick(emojis);
      s.style.left = x + 'px'; s.style.top = y + 'px'; s.style.fontSize = rand(16, 30).toFixed(0) + 'px'; D.body.appendChild(s);
      const a = rand(-Math.PI * .95, -Math.PI * .05), v = rand(120, 300) * (power || 1), dx = Math.cos(a) * v, dy = Math.sin(a) * v;
      s.animate([{transform: 'translate(0,0) rotate(0deg) scale(.6)', opacity: 1}, {transform: 'translate(' + (dx * .6).toFixed(0) + 'px,' + (dy * .8).toFixed(0) + 'px) rotate(' + rand(-200, 200).toFixed(0) + 'deg) scale(1.1)', opacity: 1, offset: .45}, {transform: 'translate(' + dx.toFixed(0) + 'px,' + (dy + 280).toFixed(0) + 'px) rotate(' + rand(-420, 420).toFixed(0) + 'deg) scale(.9)', opacity: 0}], {duration: rand(1100, 1900), easing: 'cubic-bezier(.2,.7,.4,1)', fill: 'forwards'}).onfinish = () => s.remove();
    }
  }

  /* ---------- number flash on change + overdue wiggle ---------- */
  const NUM = /^[\d,]+(\.\d+)?%?$/;
  const SEL = '.kpi b,a.tile b,.tile b,.hk b,.hero .hs b';
  function flashEl(el) { el.classList.remove('fxflash'); void el.offsetWidth; el.classList.add('fxflash'); setTimeout(() => el.classList.remove('fxflash'), 1400); }
  let scanQ = 0, refreshAt = 0;
  const updN = $('updated'); if (updN && window.MutationObserver) new MutationObserver(() => { refreshAt = Date.now(); }).observe(updN, {childList: true, characterData: true, subtree: true});
  function scan() {
    scanQ = 0;
    D.querySelectorAll(SEL).forEach(el => { const t = el.textContent.trim(); if (!NUM.test(t)) return; const p = el.dataset.cu; if (p !== undefined && p !== t && Date.now() - refreshAt < 6000) flashEl(el); el.dataset.cu = t; });
    D.querySelectorAll('a.tile[href*="f=stale"],a.tile[href*="f=paydue"]').forEach(a => {
      const b = a.querySelector('b'), n = b ? parseFloat((b.dataset.cu || b.textContent || '0').replace(/,/g, '')) || 0 : 0;
      const ic = a.querySelector('.tl i'); if (ic) ic.classList.toggle('fxwig', n > 0);
    });
  }
  const mn = D.querySelector('.main');
  if (mn && window.MutationObserver) new MutationObserver(() => { if (!scanQ) scanQ = requestAnimationFrame(scan); }).observe(mn, {childList: true, subtree: true, characterData: true});
  scan();

  /* ---------- page transitions ---------- */
  addEventListener('hashchange', () => {
    if (off || !mn) return;
    Array.from(mn.children).forEach(c => { if (c.classList.contains('top') || c.classList.contains('gfbar')) return; c.classList.remove('fxin'); void c.offsetWidth; c.classList.add('fxin'); setTimeout(() => c.classList.remove('fxin'), 700); });
    mn.classList.add('fxrows'); setTimeout(() => mn.classList.remove('fxrows'), 1000);
  });

  /* ---------- loading pill ---------- */
  const pill = D.createElement('div'); pill.id = 'fxLoad'; pill.hidden = true;
  pill.innerHTML = '<span class="sw"><svg viewBox="0 0 200 190" aria-hidden="true"><use href="#mbface"/></svg></span><span>Fetching deals… <em>🍌</em></span>';
  B.appendChild(pill);
  const upd = $('updated'), rf = $('btnRefresh');
  function pillCheck() {
    const a = upd ? upd.textContent : '', b = rf ? rf.textContent : '';
    pill.hidden = !(/^\s*Loading/i.test(a) || /Refreshing/i.test(b));
  }
  [upd, rf].forEach(n => { if (n && window.MutationObserver) new MutationObserver(pillCheck).observe(n, {childList: true, characterData: true, subtree: true}); });
  pillCheck();

  /* ---------- after a save: tick, confetti, row glow ---------- */
  function saved() {
    if (off) return;
    D.querySelectorAll('#fxTick').forEach(n => n.remove());
    const t = D.createElementNS('http://www.w3.org/2000/svg', 'svg'); t.id = 'fxTick'; t.setAttribute('viewBox', '0 0 84 84');
    t.innerHTML = '<circle cx="42" cy="42" r="38"/><path d="M26 43l11 11 21-24"/>'; B.appendChild(t); setTimeout(() => t.remove(), 1800);
    burst(innerWidth / 2, innerHeight - 90, 16, ['🎉', '✨', '🍌', '🎊'], .9);
    try {
      let best = null, bt = 0; Object.keys(TOUCHED).forEach(id => { if (TOUCHED[id] > bt) { bt = TOUCHED[id]; best = id; } });
      if (best && Date.now() - bt < 4000 && typeof view !== 'undefined') {
        const i = view.findIndex(x => String(x.ID) === String(best)), rows = D.querySelectorAll('#dash tbody tr');
        const tr = i >= 0 ? rows[i - page * PER] : null; if (tr) { tr.classList.remove('fxhi'); void tr.offsetWidth; tr.classList.add('fxhi'); setTimeout(() => tr.classList.remove('fxhi'), 2800); }
      }
    } catch (e) {}
  }

  window.__fx = {burst: burst, saved: saved};
})();
</script>
<!--/FX-->
<!--IMG-->
<style>
.emo{width:1.2em;height:1.2em;vertical-align:-.22em;display:inline-block;object-fit:contain;pointer-events:none;-webkit-user-drag:none;user-select:none}
.tile .tl i:has(>img.emo){width:54px;height:54px;background:none!important;border-radius:0}
.tile .tl i>img.emo{width:54px;height:54px;vertical-align:0}
.tile .tl{align-items:center;gap:10px}
.hero .hs i:has(>img.emo){width:46px;height:46px;background:none!important}
.hero .hs i>img.emo{width:46px;height:46px;vertical-align:0}
a.qb i:has(>img.emo){width:50px;height:50px;background:none!important}
a.qb i>img.emo{width:50px;height:50px;vertical-align:0}
.nav a i{width:28px}.nav a i>img.emo{width:28px;height:28px;vertical-align:0}
.shell.col .nav a i>img.emo{width:30px;height:30px}
.emo{filter:drop-shadow(0 2px 3px rgba(11,58,68,.18))}
@media (prefers-color-scheme:dark){.hero .hs i:has(>img.emo){background:none!important}}
.av.pic{background-size:cover!important;background-position:center!important;background-repeat:no-repeat;color:transparent!important;border:2px solid #fff!important;box-shadow:0 2px 8px rgba(20,60,70,.25)}
a.ucard .ucov{height:96px;margin:-14px -16px 12px;border-radius:14px 14px 0 0;background-size:cover;background-position:center;background-color:color-mix(in srgb,var(--accent) 18%,var(--card))}
</style>
<script>
(function () {
  var ROOT = <?php echo json_encode(rtrim(str_replace('\\', '/', dirname((string)$_SERVER['SCRIPT_NAME'])), '/') . '/'); ?>, BASE = ROOT + 'images/icons/', UBASE = ROOT + 'images/universities/', MAP = {"⚑": "triangular_flag", "🚩": "triangular_flag", "🔍": "left_pointing_magnifying_glass", "💰": "money_bag", "🎓": "graduation_cap", "🏠": "home", "📊": "bar_chart", "📋": "clipboard", "🔥": "fire", "📑": "bookmark_tabs", "👤": "bust_in_silhouette", "🔑": "key", "💬": "speech_balloon", "🕵": "detective", "⏰": "alarm_clock", "🔎": "right_pointing_magnifying_glass", "🚪": "door", "🏛": "university", "🤝": "handshake", "⏳": "hourglass_with_flowing_sand", "📵": "no_phone", "📚": "books", "✨": "sparkles", "📘": "blue_book", "📅": "calendar", "💸": "money_with_wings", "📄": "page_facing_up", "📈": "chart_with_upwards_trend", "✏": "pencil", "🕘": "clock_face_nine_oclock", "✅": "check_mark_green", "🎯": "direct_hit", "👥": "busts_in_silhouette", "🔹": "small_blue_diamond", "❔": "question_mark", "🔁": "repeat", "📞": "telephone_receiver", "🧩": "jigsaw_puzzle_piece", "⚠": "warning", "💳": "credit_card", "🍌": "banana", "🌴": "palm_tree", "🎉": "party_popper", "🧹": "broom", "🕐": "clock_face_one_oclock", "🔄": "refresh", "📥": "inbox_tray", "🌗": "half_moon", "👍": "thumbs_up", "❌": "cross_mark", "📂": "open_file_folder", "👀": "eyes", "🌙": "crescent_moon", "☀": "sun", "🎛": "control_knobs", "📍": "round_pushpin", "🕰": "mantelpiece_clock", "📝": "memo", "☕": "hot_beverage", "🏅": "sports_medal", "🎊": "confetti_ball", "📣": "cheering_megaphone", "🌤": "white_sun_with_small_cloud", "🌞": "sun_with_face"};
  var keys = Object.keys(MAP).sort(function (a, b) { return b.length - a.length; });
  var RE = new RegExp('(?:' + keys.map(function (k) { return k.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }).join('|') + ')\uFE0F?', 'g');
  var SKIP = {SCRIPT: 1, STYLE: 1, TEXTAREA: 1, OPTION: 1, TITLE: 1, NOSCRIPT: 1, INPUT: 1, SELECT: 1};
  var FAILED = {}, on = false, queue = new Set(), raf = 0;
  function keyOf(m) { return m.replace(/\uFE0F/g, ''); }
  function replaceNode(n) {
    var v = n.nodeValue; RE.lastIndex = 0; var m, last = 0, frag = null;
    while ((m = RE.exec(v))) {
      var k = keyOf(m[0]); if (!MAP[k] || FAILED[k]) continue;
      if (!frag) frag = document.createDocumentFragment();
      if (m.index > last) frag.appendChild(document.createTextNode(v.slice(last, m.index)));
      var im = document.createElement('img'); im.className = 'emo'; im.alt = k; im.draggable = false; im.decoding = 'async';
      (function (img, key) { img.onerror = function () { FAILED[key] = 1; if (img.parentNode) img.parentNode.replaceChild(document.createTextNode(key), img); }; })(im, k);
      im.src = BASE + MAP[k] + '.svg'; frag.appendChild(im); last = m.index + m[0].length;
    }
    if (frag) { if (last < v.length) frag.appendChild(document.createTextNode(v.slice(last))); n.parentNode.replaceChild(frag, n); }
  }
  function ok(n) { var p = n.parentNode; if (!p || SKIP[p.nodeName]) return false; if (p.closest && p.closest('svg,[contenteditable="true"]')) return false; return true; }
  function proc(root) {
    if (!on || !root) return;
    if (root.nodeType === 3) { if (ok(root)) replaceNode(root); return; }
    if (root.nodeType !== 1 || SKIP[root.nodeName]) return;
    var tw = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null), list = [], n;
    while ((n = tw.nextNode())) { RE.lastIndex = 0; if (RE.test(n.nodeValue) && ok(n)) list.push(n); }
    list.forEach(replaceNode);
  }
  /* ---------- university photos: images/universities/<name>.jpg ---------- */
  var UIDX = null;
  function norm(s) { return String(s || '').toLowerCase().replace(/[^a-z0-9]+/g, ''); }
  function picFor(name) {
    if (!UIDX) return null;
    var full = norm(name); if (UIDX[full]) return UIDX[full];
    var pm = /\(([^)]+)\)/.exec(name); if (pm && UIDX[norm(pm[1])]) return UIDX[norm(pm[1])];
    var toks = String(name).toLowerCase().split(/[^a-z0-9]+/).filter(Boolean);
    var acr = toks.map(function (t) { return t.charAt(0); }).join(''); if (acr.length >= 2 && UIDX[acr]) return UIDX[acr];
    var acr2 = toks.filter(function (t) { return ['of', 'the', 'and', 'for'].indexOf(t) < 0; }).map(function (t) { return t.charAt(0); }).join(''); if (acr2.length >= 2 && UIDX[acr2]) return UIDX[acr2];
    for (var i = 0; i < toks.length; i++) if (toks[i].length >= 3 && UIDX[toks[i]]) return UIDX[toks[i]];
    return null;
  }
  function setPic(av, file) { av.style.backgroundImage = 'url("' + UBASE + encodeURIComponent(file) + '")'; av.textContent = ''; av.classList.add('pic'); }
  function uniPics(root) {
    if (!UIDX) return;
    (root || document).querySelectorAll('a.ucard:not([data-ui]), .rk:not([data-ui]), .pkc:not(.all):not([data-ui])').forEach(function (el) {
      el.setAttribute('data-ui', '1');
      var nm = el.matches('a.ucard') ? el.querySelector('.un b') : el.matches('.rk') ? el.querySelector('.nm') : el.querySelector('.tx b');
      var name = nm ? (nm.getAttribute('title') || nm.textContent) : ''; var f = name ? picFor(name) : null; if (!f) return;
      var av = el.querySelector('.av'); if (av) setPic(av, f);
      if (el.matches('a.ucard')) { var c = document.createElement('div'); c.className = 'ucov'; c.style.backgroundImage = 'url("' + UBASE + encodeURIComponent(f) + '")'; el.insertBefore(c, el.firstChild); }
    });
  }
  function flush() { raf = 0; var q = Array.from(queue); queue.clear(); q.forEach(function (n) { if (n.isConnected !== false) proc(n); }); uniPics(document); }
  function sched(n) { queue.add(n); if (!raf) raf = requestAnimationFrame(flush); }
  var mo = new MutationObserver(function (ms) {
    ms.forEach(function (m) {
      if (m.type === 'characterData') sched(m.target);
      else m.addedNodes.forEach(function (n) { if (n.nodeType === 3 || n.nodeType === 1) sched(n); });
    });
  });
  function start() { on = true; proc(document.body); mo.observe(document.body, {childList: true, subtree: true, characterData: true}); }
  /* ---------- hero banner photo: images/university_hero.png (or .webp/.jpg) ---------- */
  (function () {
    var names = ['university_hero.png', 'university_hero.webp', 'university_hero.jpg', 'university_hero.jpeg'], dirs = [ROOT + 'images/', ROOT + 'images/images/'], list = [];
    dirs.forEach(function (d) { names.forEach(function (n) { list.push(d + n); }); });
    (function next(i) {
      if (i >= list.length) return;
      var im = new Image();
      im.onload = function () {
        var st = document.createElement('style');
        st.textContent = '.hscene{left:0!important}' +
          '.hscene .bld{left:285px!important;right:300px!important;width:auto!important;top:8px!important;bottom:0!important;height:auto!important;aspect-ratio:auto!important;background:url("' + list[i] + '") center bottom/contain no-repeat;-webkit-mask-image:linear-gradient(90deg,transparent 0,#000 3%,#000 97%,transparent 100%);mask-image:linear-gradient(90deg,transparent 0,#000 3%,#000 97%,transparent 100%);filter:drop-shadow(0 8px 14px rgba(11,58,68,.35))}' +
          '.hscene .bld svg{display:none!important}' +
          '.hscene .stack{display:none!important}';
        document.head.appendChild(st);
        var ratio = im.naturalWidth / im.naturalHeight;
        function fit() {
          var el = document.querySelector('.hscene .bld'); if (!el) return;
          var r = el.getBoundingClientRect(), bw = r.width, bh = r.height; if (!bw || !bh) return;
          var w, h;
          if (bw >= bh * ratio) { h = bh; w = Math.min(bw, bh * ratio * 1.12); } else { w = bw; h = bw / ratio; }
          el.style.backgroundSize = Math.round(w) + 'px ' + Math.round(h) + 'px';
        }
        fit(); window.addEventListener('resize', fit); setTimeout(fit, 600); setTimeout(fit, 2500);
        try { if (window.ResizeObserver) new ResizeObserver(fit).observe(document.body); } catch (e) {}
      };
      im.onerror = function () { next(i + 1); };
      im.src = list[i];
    })(0);
  })();
  var CANDS = [ROOT + 'images/icons/', ROOT + 'images/images/icons/', ROOT + 'images/', ROOT + 'icons/'];
  (function tryNext(i) {
    if (i >= CANDS.length) { try { console.warn('[images] icons not found (university.svg). Tried: ' + CANDS.join(', ')); } catch (e) {} return; }
    var pr = new Image(); pr.onload = function () { BASE = CANDS[i]; start(); }; pr.onerror = function () { tryNext(i + 1); }; pr.src = CANDS[i] + 'university.svg';
  })(0);
  try {
    fetch(ROOT + 'index.php?imglist=1', {credentials: 'same-origin', cache: 'no-store'}).then(function (r) { return r.ok ? r.json() : []; }).then(function (res) {
      var list = Array.isArray(res) ? res : (res.files || []); if (res && res.dir) UBASE = ROOT + res.dir + '/';
      UIDX = {}; (list || []).forEach(function (f) { var k = norm(f.replace(/\.[^.]+$/, '')); if (k && !UIDX[k]) UIDX[k] = f; });
      if (list && list.length) { document.querySelectorAll('[data-ui]').forEach(function (e) { e.removeAttribute('data-ui'); }); uniPics(document); }
    }).catch(function () {});
  } catch (e) {}
})();
</script>
<!--/IMG-->


<!--POP-->
<script>
(function () {
  /* keep every dropdown panel inside the viewport: always opens downward; flips left/right as needed */
  var SEL = '.cbp, .panel';
  function clamp(el) {
    if (!el || el.hidden) return;
    var cs = getComputedStyle(el); if (cs.display === 'none') return;
    var list = el.querySelector('.cbl'); if (list) list.style.maxHeight = '';
    el.style.left = ''; el.style.right = ''; el.style.top = ''; el.style.bottom = '';
    var vw = document.documentElement.clientWidth, vh = window.innerHeight, m = 8, r = el.getBoundingClientRect();
    if (r.right > vw - m) { el.style.left = 'auto'; el.style.right = '0'; r = el.getBoundingClientRect(); }
    if (r.left < m) { var par = el.offsetParent ? el.offsetParent.getBoundingClientRect() : {left: 0}; el.style.right = 'auto'; el.style.left = (m - par.left) + 'px'; r = el.getBoundingClientRect(); }
    if (el.classList.contains('cbp')) { try { el.scrollIntoView({block: 'nearest', behavior: 'smooth'}); } catch (e) {} }
  }
  function all() { document.querySelectorAll(SEL).forEach(clamp); }
  new MutationObserver(function (ms) {
    ms.forEach(function (m) { if (m.target.matches && m.target.matches(SEL)) clamp(m.target); });
  }).observe(document.body, {attributes: true, attributeFilter: ['hidden', 'class'], subtree: true});
  window.addEventListener('resize', all);
  document.addEventListener('input', function (e) { var p = e.target.closest && e.target.closest('.cbp'); if (p) setTimeout(function () { clamp(p); }, 0); });
})();
</script>
<!--/POP-->
<!--ND-->
<style>
.ndov{position:fixed;inset:0;z-index:9000;display:grid;place-items:center;padding:16px;background:rgba(8,28,30,.55);backdrop-filter:blur(4px);animation:ndIn .25s ease both}
@keyframes ndIn{from{opacity:0}to{opacity:1}}
.ndbox{width:min(580px,100%);max-height:min(80vh,680px);display:flex;flex-direction:column;background:var(--card);color:var(--text);border:1px solid var(--line);border-radius:20px;box-shadow:0 30px 80px rgba(0,0,0,.4);overflow:hidden;animation:ndUp .35s cubic-bezier(.2,1.2,.4,1) both}
@keyframes ndUp{from{opacity:0;transform:translateY(24px) scale(.96)}to{opacity:1;transform:none}}
.ndhd{display:flex;align-items:center;gap:12px;padding:18px 20px 12px;background:linear-gradient(120deg,rgba(23,179,166,.18),rgba(255,154,60,.14))}
.ndhd .ndbell{font-size:30px;line-height:1}
.ndhd h2{margin:0;font-size:19px}.ndhd p{margin:2px 0 0;font-size:13px;color:var(--muted)}
.ndx{margin-left:auto;border:0;background:transparent;color:var(--muted);font-size:22px;cursor:pointer;line-height:1;padding:4px 8px;border-radius:8px}.ndx:hover{background:var(--bg)}
.ndbd{padding:6px 20px 8px;overflow:auto}
.ndg{margin:12px 0}
.ndgh{display:flex;align-items:center;gap:8px;font-weight:800;font-size:14.5px;margin-bottom:6px}
.ndgh .ndc{margin-left:auto;font-size:12px;font-weight:800;padding:3px 10px;border-radius:999px;background:linear-gradient(135deg,#17b3a6,#0f8f85);color:#fff}
.ndr{display:flex;align-items:center;gap:10px;width:100%;text-align:left;padding:9px 12px;margin:4px 0;border:1px solid var(--line);border-radius:12px;background:var(--bg);color:var(--text);cursor:pointer;font:inherit;font-size:13.5px}
.ndr:hover{border-color:#17b3a6;box-shadow:0 4px 14px rgba(23,179,166,.18)}
.ndr b{font-weight:700}.ndr small{display:block;color:var(--muted);font-size:12px;margin-top:1px}
.ndr .ndt{margin-left:auto;font-size:11.5px;color:var(--muted);white-space:nowrap}
.ndmore{font-size:12.5px;color:var(--muted);padding:2px 4px}
.ndft{display:flex;justify-content:flex-end;gap:10px;padding:12px 20px 16px;border-top:1px solid var(--line)}
.ndft button{padding:9px 18px;border-radius:10px;font:inherit;font-weight:700;border:0;cursor:pointer;background:linear-gradient(135deg,#17b3a6,#0f8f85);color:#fff}
</style>
<script>
(function () {
  var tries = 0, ran = false;
  function nm(r) { return r._NAME || r.TITLE || ('Deal ' + r.ID); }
  function colBy(role, re) { try { return cols.find(function (c) { return c.role === role; }) || cols.find(function (c) { return re.test(c.label); }) || null; } catch (e) { return null; } }
  function ack(max) {
    try { fetch('api.php?action=nd', {method: 'POST', credentials: 'same-origin', keepalive: true, headers: {'X-CSRF-Token': CSRF, 'Content-Type': 'application/json'}, body: JSON.stringify({max: max})}); } catch (e) {}
  }
  function when(r) { try { return r.DATE_CREATE && typeof niceStored === 'function' ? niceStored(r.DATE_CREATE) : ''; } catch (e) { return ''; } }
  function show(fresh, maxId) {
    var tc = colBy('team', /team/i), pc = colBy('program', /^program/i), groups = {}, order = [];
    fresh.forEach(function (r) {
      var t = tc ? String(r[tc.key] || '').trim() : ''; if (!t) t = '(No team)';
      if (!groups[t]) { groups[t] = []; order.push(t); }
      groups[t].push(r);
    });
    order.sort(function (a, b) { return groups[b].length - groups[a].length; });
    var h = '';
    order.forEach(function (t) {
      var L = groups[t].slice().sort(function (a, b) { return Number(b.ID) - Number(a.ID); });
      h += '<div class="ndg"><div class="ndgh"><span>👥 ' + esc(t) + '</span><span class="ndc">' + L.length + ' new</span></div>';
      L.slice(0, 6).forEach(function (r) {
        var u = '', p = '';
        try { u = uniOf(r); if (u === UNASSIGNED) u = ''; } catch (e) {}
        if (pc) p = String(r[pc.key] || '').trim();
        h += '<button type="button" class="ndr" data-id="' + esc(r.ID) + '"><span><b>' + esc(nm(r)) + '</b><small>#' + esc(r.ID) + (u ? ' · ' + esc(u) : '') + (p ? ' · ' + esc(p) : '') + '</small></span><span class="ndt">' + esc(when(r)) + '</span></button>';
      });
      if (L.length > 6) h += '<div class="ndmore">+ ' + (L.length - 6) + ' more from this team</div>';
      h += '</div>';
    });
    var teams = order.length, n = fresh.length;
    var ov = document.createElement('div'); ov.className = 'ndov'; ov.setAttribute('role', 'dialog'); ov.setAttribute('aria-modal', 'true');
    ov.innerHTML = '<div class="ndbox"><div class="ndhd"><span class="ndbell">🔔</span><div><h2>' + n + ' new deal' + (n === 1 ? '' : 's') + ' created</h2><p>' + (teams === 1 ? (order[0] === '(No team)' ? 'With no team assigned yet' : 'From the ' + esc(order[0]) + ' team') : 'From ' + teams + ' teams') + ' since you last checked.</p></div><button type="button" class="ndx" aria-label="Close">×</button></div><div class="ndbd">' + h + '</div><div class="ndft"><button type="button" class="ndok">Got it</button></div></div>';
    document.body.appendChild(ov);
    function done() { ack(maxId); ov.remove(); document.removeEventListener('keydown', key); }
    function key(e) { if (e.key === 'Escape') done(); }
    document.addEventListener('keydown', key);
    ov.querySelector('.ndx').onclick = done; ov.querySelector('.ndok').onclick = done;
    ov.addEventListener('mousedown', function (e) { if (e.target === ov) done(); });
    ov.querySelectorAll('.ndr').forEach(function (b) {
      b.onclick = function () { var id = b.getAttribute('data-id'); done(); try { var r = allRows.find(function (x) { return String(x.ID) === id; }); if (r) openEdit(r); } catch (e) {} };
    });
    var ok = ov.querySelector('.ndok'); if (ok) ok.focus();
  }
  function run() {
    if (ran) return; ran = true;
    var maxId = 0; allRows.forEach(function (r) { var n = Number(r.ID) || 0; if (n > maxId) maxId = n; });
    fetch('api.php?action=nd', {credentials: 'same-origin'}).then(function (r) { return r.ok ? r.json() : null; }).then(function (j) {
      if (!j || !maxId) return;
      if (j.seen == null) { ack(maxId); return; }            // first time ever: start counting from now, don't flood
      var fresh = allRows.filter(function (r) { return (Number(r.ID) || 0) > j.seen; });
      if (fresh.length) show(fresh, maxId);
    }).catch(function () {});
  }
  var iv = setInterval(function () {
    tries++;
    if (tries > 240) { clearInterval(iv); return; }
    try {
      if (typeof allRows === 'undefined' || !allRows.length || document.getElementById('ppwel') || !document.getElementById('home') && !document.getElementById('dash')) return;
    } catch (e) { return; }
    clearInterval(iv); setTimeout(run, 600);
  }, 500);
})();
</script>
<!--/ND-->
<!--LGO-->
<style>
.lgcf{position:fixed;inset:0;z-index:9500;display:grid;place-items:center;padding:16px;background:rgba(8,28,30,.55);backdrop-filter:blur(4px);animation:ndIn .2s ease both}
.lgcb{width:min(400px,100%);padding:24px 24px 20px;text-align:center;background:var(--card);color:var(--text);border:1px solid var(--line);border-radius:20px;box-shadow:0 30px 80px rgba(0,0,0,.4);animation:ndUp .3s cubic-bezier(.2,1.2,.4,1) both}
.lgcb .lgi{font-size:42px;line-height:1}.lgcb h2{margin:10px 0 6px;font-size:20px}.lgcb p{margin:0 0 18px;color:var(--muted);font-size:14px}
.lgcb .lgr{display:flex;gap:10px}.lgcb button{flex:1;padding:11px 14px;border-radius:12px;font:inherit;font-weight:700;cursor:pointer;border:1px solid var(--line)}
.lgcb .lgs{background:linear-gradient(135deg,#17b3a6,#0f8f85);color:#fff;border-color:transparent}
.lgcb .lgy{background:transparent;color:#e11d48;border-color:rgba(225,29,72,.45)}.lgcb .lgy:hover{background:rgba(225,29,72,.1)}
</style>
<script>
(function () {
  var btn = document.getElementById('lgoBtn'), form = document.getElementById('lgoForm');
  if (!btn || !form) return;
  btn.addEventListener('click', function (e) {
    e.preventDefault();
    if (document.querySelector('.lgcf')) return;
    var ov = document.createElement('div'); ov.className = 'lgcf'; ov.setAttribute('role', 'alertdialog'); ov.setAttribute('aria-modal', 'true');
    ov.innerHTML = '<div class="lgcb"><div class="lgi">🚪</div><h2>Confirm log out</h2><p>Are you sure you want to log out of your account? You will need to sign in again to continue.</p><div class="lgr"><button type="button" class="lgs">Stay logged in</button><button type="button" class="lgy">Yes, log out</button></div></div>';
    document.body.appendChild(ov);
    function close() { ov.remove(); document.removeEventListener('keydown', key); }
    function key(ev) { if (ev.key === 'Escape') close(); }
    document.addEventListener('keydown', key);
    ov.querySelector('.lgs').onclick = close;
    ov.querySelector('.lgy').onclick = function () { form.submit(); };
    ov.addEventListener('mousedown', function (ev) { if (ev.target === ov) close(); });
    ov.querySelector('.lgs').focus();
  });
})();
</script>
<!--/LGO-->
</body></html>
