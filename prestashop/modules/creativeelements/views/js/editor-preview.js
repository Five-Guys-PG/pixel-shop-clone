/*!
 * Creative Elements - live Theme & Page Builder
 * Copyright 2019-2024 WebshopWorks.com
 */
if (!$('.elementor:empty').length && location.search.indexOf('&force=1&') < 0) {
	// redirect to preview page when content area doesn't exist
	location.href = cePreview + '&force=1&ver=' + Date.now();
}