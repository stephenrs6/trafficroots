export default file => {
	if (!window.URL || !window.URL.createObjectURL) {
		return;
	}
	return URL.createObjectURL(file);
};