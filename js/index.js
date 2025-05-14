const logoClick = () => {
	window.location.href = "/dauraksi/"
}
const year = document.getElementById("year")
year.textContent = new Date().getFullYear()

const openCenteredPopup = (url, title, w, h) => {
	// Hitung posisi tengah layar
	const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : screen.left
	const dualScreenTop = window.screenTop !== undefined ? window.screenTop : screen.top

	const width = window.innerWidth || document.documentElement.clientWidth || screen.width
	const height = window.innerHeight || document.documentElement.clientHeight || screen.height

	const left = width / 2 - w / 2 + dualScreenLeft
	const top = height / 2 - h / 2 + dualScreenTop

	const popup = window.open(
		url,
		title,
		`scrollbars=yes, width=${w}, height=${h}, top=${top}, left=${left}, resizable=no`
	)

	// Fokuskan ke popup jika dibuka
	if (window.focus) popup.focus()
}

const handleLogin = () => {
	openCenteredPopup("/dauraksi/auth/login.php", "Login DaurAksi", 500, 500)
	window.location.href = "/dauraksi/"
}

const handleRegister = () => {
	openCenteredPopup("/dauraksi/auth/register.php", "Register DaurAksi", 500, 500)
}

const handleCommunity = () => {
	window.open("https://discord.gg/cdcgVnxrvh", "_blank", "noopener,noreferrer")
}

const avatar = document.getElementById("avatar")

avatar.addEventListener("click", () => {
	window.location.href = "/dauraksi/pages/profile.php"
})
