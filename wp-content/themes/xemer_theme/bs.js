// bs.js
const browserSync = require("browser-sync").create();

browserSync.init({
	proxy: "http://localhost:81/wp/wp_clone/", // ← chính xác tên site bạn đang chạy trên localhost
	files: ["**/*.php", "**/*.scss", "**/*.js"],
	reloadDelay: 200,
	open: true,
});
