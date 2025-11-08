// Authentication utility functions for FITDECK

// Check if user is logged in
function checkLoginStatus() {
    var isLoggedIn = sessionStorage.getItem('isLoggedIn') || localStorage.getItem('isLoggedIn');
    var userType = sessionStorage.getItem('userType') || localStorage.getItem('userType');
    
    return {
        isLoggedIn: isLoggedIn === 'true',
        userType: userType,
        email: sessionStorage.getItem('userEmail') || localStorage.getItem('userEmail')
    };
}

// Logout function
function logout() {
    // Clear session storage
    sessionStorage.removeItem('isLoggedIn');
    sessionStorage.removeItem('userType');
    sessionStorage.removeItem('userEmail');
    
    // Clear local storage
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userType');
    localStorage.removeItem('userEmail');
    
    // Redirect to signin page
    window.location.href = '../signin.html';
}

// Protect admin pages - redirect to signin if not logged in as admin
function protectAdminPage() {
    var auth = checkLoginStatus();
    
    if (!auth.isLoggedIn || auth.userType !== 'admin') {
        alert('Please login as admin to access this page.');
        window.location.href = '../signin.html';
        return false;
    }
    
    return true;
}

// Protect user pages - redirect to signin if not logged in
function protectUserPage() {
    var auth = checkLoginStatus();
    
    if (!auth.isLoggedIn) {
        alert('Please login to access this page.');
        window.location.href = 'signin.html';
        return false;
    }
    
    return true;
}

// Display user info in navigation (optional)
function displayUserInfo() {
    var auth = checkLoginStatus();
    
    if (auth.isLoggedIn) {
        var userInfoElement = document.getElementById('user-info');
        if (userInfoElement) {
            userInfoElement.innerHTML = `
                <span style="color: var(--first-color);">Logged in as: ${auth.email}</span>
                <button onclick="logout()" style="margin-left: 10px; padding: 5px 15px; background: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer;">Logout</button>
            `;
        }
    }
}

