/* globals require */
require('./bootstrap');

let { title } = document;

if (title.includes('Publisher Stats')) {
    require('./pages/pub-stats');
}
if (title.includes('Sites')) {
    require('./pages/sites');
}
if (title.includes('Campaigns')) {
    require('./pages/campaigns');
}

// if (title.includes('Create Campaign')) {
//     require('./pages/create_campaign');
// }

if (title.includes('Advertisers')) {
    require('./pages/buyers');
}