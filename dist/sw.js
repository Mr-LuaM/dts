if(!self.define){let e,s={};const i=(i,n)=>(i=new URL(i+".js",n).href,s[i]||new Promise((s=>{if("document"in self){const e=document.createElement("script");e.src=i,e.onload=s,document.head.appendChild(e)}else e=i,importScripts(i),s()})).then((()=>{let e=s[i];if(!e)throw new Error(`Module ${i} didn’t register its module`);return e})));self.define=(n,r)=>{const l=e||("document"in self?document.currentScript.src:"")||location.href;if(s[l])return;let t={};const o=e=>i(e,l),d={module:{uri:l},exports:t,require:o};s[l]=Promise.all(n.map((e=>d[e]||o(e)))).then((e=>(r(...e),t)))}}define(["./workbox-3e911b1d"],(function(e){"use strict";self.skipWaiting(),e.clientsClaim(),e.precacheAndRoute([{url:"assets/AboutView-B_lQaN46.js",revision:null},{url:"assets/AboutView-C6Dx7pxG.css",revision:null},{url:"assets/index-C4gd48Lf.css",revision:null},{url:"assets/index-D0jRS1eN.js",revision:null},{url:"index.html",revision:"15db7273c8b568694105b51cfebb28cf"},{url:"registerSW.js",revision:"1872c500de691dce40960bb85481de07"},{url:"pwa-192x192.png",revision:"cd1c3da7ce1cfe618e86602585564ef9"},{url:"pwa-512x512.png",revision:"f168f44ab8936720f492ce620d0531ec"},{url:"pwa-maskable-192x192.png",revision:"8ed5bccfdd0035014908db7094221d7b"},{url:"pwa-maskable-512x512.png",revision:"2983689f048b36466577c27370245bf0"},{url:"manifest.webmanifest",revision:"e555f4e69068e4b4df162478fd10febd"}],{}),e.cleanupOutdatedCaches(),e.registerRoute(new e.NavigationRoute(e.createHandlerBoundToURL("index.html")))}));
