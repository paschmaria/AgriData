//Service worker with Cache-first network

var CACHE = 'agridata-precache';
var precacheFiles = [
  '/',
  '403.html',
  'login.php',
  'register.php',
  'forgot-password.php',
  'register-farmer.php',
  'farmer-overview.php',
  'farmer-biodata.php',
  'farmer-demography.php',
  'farmer-cropinfo.php',
  'farmer-profile.php',
  'profile.php',
  'reports.php',
  'register-farmer.php',
  'site.webmanifest',
  'assets/images/image.png',
  'assets/images/logo.png',
  'assets/images/logo_2.png',
  'assets/js/require.min.js',
  'assets/js/dashboard.js',
  'assets/js/lga.js',
  'assets/js/lga_data.js',
  'assets/js/core',
  'assets/js/vendors/jquery-3.2.1.min',
  'assets/js/vendors/bootstrap.bundle.min',
  'assets/js/vendors/jquery.sparkline.min',
  'assets/js/vendors/selectize.min',
  'assets/js/vendors/jquery.tablesorter.min',
  'assets/js/vendors/circle-progress.min',
  'assets/js/vendors/chart.bundle.min',
  'assets/css/dashboard.css'
];

//Install stage sets up the cache-array to configure pre-cache content
self.addEventListener('install', function(evt) {
  console.log('[PWA Builder] The service worker is being installed.');
  evt.waitUntil(precache().then(function() {
    console.log('[PWA Builder] Skip waiting on install');
    return self.skipWaiting();
  }));
});


//allow sw to control of current page
self.addEventListener('activate', function(event) {
  console.log('[PWA Builder] Claiming clients for current page');
  return self.clients.claim();
});

self.addEventListener('fetch', function(evt) {
  console.log('[PWA Builder] The service worker is serving the asset.'+ evt.request.url);
  evt.respondWith(fromCache(evt.request).catch(fromServer(evt.request)));
  evt.waitUntil(update(evt.request));
});


function precache() {
  return caches.open(CACHE).then(function (cache) {
    return cache.addAll(precacheFiles);
  });
}

function fromCache(request) {
  //we pull files from the cache first thing so we can show them fast
  return caches.open(CACHE).then(function (cache) {
    return cache.match(request).then(function (matching) {
      return matching || Promise.reject('no-match');
    });
  });
}

function update(request) {
  //this is where we call the server to get the newest version of the 
  //file to use the next time we show view
  return caches.open(CACHE).then(function (cache) {
    return fetch(request).then(function (response) {
      return cache.put(request, response);
    });
  });
}

function fromServer(request){
  //this is the fallback if it is not in the cache to go to the server and get it
  return fetch(request).then(function(response){ return response});
}

// importScripts('https://storage.googleapis.com/workbox-cdn/releases/3.4.1/workbox-sw.js');

// if (workbox) {
//   console.log(`Yay! Workbox is loaded 🎉`);
// } else {
//   console.log(`Boo! Workbox didn't load 😬`);
// }

// workbox.routing.registerRoute(
//   new RegExp('.*\.js'),
//   workbox.strategies.networkFirst()
// );

// workbox.routing.registerRoute(
//   // Cache CSS files
//   new RegExp('.*\.{css,php}'),
//   // Use cache but update in the background ASAP
//   workbox.strategies.staleWhileRevalidate({
//     // Use a custom cache name
//     cacheName: 'other-cache',
//   })
// );