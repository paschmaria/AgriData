importScripts('https://storage.googleapis.com/workbox-cdn/releases/3.4.1/workbox-sw.js');

if (workbox) {
  console.log(`Yay! Workbox is loaded 🎉`);
} else {
  console.log(`Boo! Workbox didn't load 😬`);
}

workbox.routing.registerRoute(
  new RegExp('.*\.js'),
  workbox.strategies.networkFirst()
);

workbox.routing.registerRoute(
  // Cache CSS files
  new RegExp('.*\.css'),
  // Use cache but update in the background ASAP
  workbox.strategies.staleWhileRevalidate({
    // Use a custom cache name
    cacheName: 'css-cache',
  })
);

// self.addEventListener('install', function(e) {
//   e.waitUntil(
//     caches.open('the-magic-cache').then(function(cache) {
//       return cache.addAll([
//         './403.html',
//         './login.php',
//         './register.php',
//         './forgot-password.php',
//         './register-farmer.php',
//         './farmer-overview.php',
//         './farmer-biodata.php',
//         './farmer-demography.php',
//         './farmer-cropinfo.php',
//         './farmer-profile.php',
//         './profile.php',
//         './reports.php',
//         './register-farmer.php',
//         './site.webmanifest',
//         './image.png',
//         './logo.png',
//         './logo_2.png',
//         './require.min.js',
//         './dashboard.js',
//         './lga.js',
//         './lga_data.js',
//         './assets/js/core',
//         './assets/js/vendors/jquery-3.2.1.min',
//         './assets/js/vendors/bootstrap.bundle.min',
//         './assets/js/vendors/jquery.sparkline.min',
//         './assets/js/vendors/selectize.min',
//         './assets/js/vendors/jquery.tablesorter.min',
//         './assets/js/vendors/circle-progress.min',
//         './assets/js/vendors/chart.bundle.min',
//         './dashboard.css'
//       ]);
//     })
//   );
// });
// self.addEventListener('fetch', function(event) {
//   console.log(event.request.url);
// });