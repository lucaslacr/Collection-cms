document.getElementById('changepasswordview').addEventListener('click', function() {
    const passwordInput = document.getElementById('databasepassword');
    const isDarkImg = this.querySelector('.isdark');
    const isLightImg = this.querySelector('.islight');
    
    // Basculer le type du champ
    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
    
    // Changer les images
    isDarkImg.src = isDarkImg.src.includes('eye-d.svg') ? '../admin-assets/see-d.svg' : '../admin-assets/eye-d.svg';
    isLightImg.src = isLightImg.src.includes('eye-l.svg') ? '../admin-assets/see-l.svg' : '../admin-assets/eye-l.svg';
});
