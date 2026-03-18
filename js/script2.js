// Global variables
let currentUser = null;
let isLoggedIn = false;
let currentSection = 'dashboard';

// DOM loaded event
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is already logged in
    checkLoginStatus();
    
    // Add event listeners
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    // Login form
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    // Register form
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
}

// Show/Hide modals
function showLoginModal() {
    document.getElementById('loginModal').style.display = 'block';
}

function showRegisterModal() {
    document.getElementById('registerModal').style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Show login modal
function showLogin() {
    document.getElementById('loginModal').style.display = 'block';
}

// Show register modal
function showRegister() {
    document.getElementById('registerModal').style.display = 'block';
}

// Close modal
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Handle login
async function handleLogin(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const loginData = {
        email: formData.get('email'),
        password: formData.get('password')
    };
    
    try {
        const response = await fetch('php/auth.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'login',
                ...loginData
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            currentUser = result.data.user;
            isLoggedIn = true;
            
            // Store user data in session
            sessionStorage.setItem('user', JSON.stringify(currentUser));
            sessionStorage.setItem('isLoggedIn', 'true');
            
            showNotification('Login successful! Welcome to Learn.lk', 'success');
            closeModal('loginModal');
            
            // Redirect to dashboard
            showDashboard();
        } else {
            showNotification(result.message || 'Login failed', 'error');
        }
    } catch (error) {
        console.error('Login error:', error);
        showNotification('An error occurred during login', 'error');
    }
}

// Handle registration
async function handleRegister(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const password = formData.get('password');
    const confirmPassword = formData.get('confirmPassword');
    
    // Validate password match
    if (password !== confirmPassword) {
        showNotification('Passwords do not match', 'error');
        return;
    }
    
    const registerData = {
        fullName: formData.get('fullName'),
        email: formData.get('email'),
        password: password,
        userType: formData.get('userType')
    };
    
    try {
        const response = await fetch('php/auth.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'register',
                ...registerData
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Registration successful! Redirecting to login...', 'success');
            closeModal('registerModal');
            
            // Auto redirect to login
            setTimeout(() => {
                showLoginModal();
            }, 1500);
        } else {
            showNotification(result.message || 'Registration failed', 'error');
        }
    } catch (error) {
        console.error('Registration error:', error);
        showNotification('An error occurred during registration', 'error');
    }
}

// Check login status
function checkLoginStatus() {
    const storedUser = sessionStorage.getItem('user');
    const storedLoginStatus = sessionStorage.getItem('isLoggedIn');
    
    if (storedUser && storedLoginStatus === 'true') {
        currentUser = JSON.parse(storedUser);
        isLoggedIn = true;
        showDashboard();
    }
}

// Show dashboard
function showDashboard() {
    // Hide homepage elements
    document.querySelector('.hero').style.display = 'none';
    document.querySelector('.features').style.display = 'none';
    document.querySelector('.navbar').style.display = 'none';
    document.querySelector('.footer').style.display = 'none';
    
    // Show dashboard
    document.getElementById('dashboard').style.display = 'flex';
    
    // Setup dashboard
    setupDashboard();
}

// Setup dashboard
function setupDashboard() {
    // Update user info in sidebar
    document.getElementById('userName').textContent = currentUser.full_name;
    document.getElementById('userRole').textContent = currentUser.user_type;
    
    // Set user avatar
    const userAvatar = document.getElementById('userAvatar');
    if (currentUser.photo_url) {
        userAvatar.innerHTML = `<img src="${currentUser.photo_url}" alt="Profile">`;
    } else {
        userAvatar.innerHTML = currentUser.full_name.charAt(0).toUpperCase();
    }
    
    // Setup sidebar menu based on user type
    setupSidebarMenu();
    
    // Load dashboard content
    loadDashboardContent();
}

// Setup sidebar menu
function setupSidebarMenu() {
    const sidebarMenu = document.getElementById('sidebarMenu');
    let menuHTML = '';
    
    // Common menu items
    menuHTML += `
        <li><a href="#" onclick="navigateToSection('dashboard')" class="active">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a></li>
        <li><a href="#" onclick="navigateToSection('profile')">
            <i class="fas fa-user"></i> Profile
        </a></li>
    `;
    
    // Student specific menu
    if (currentUser.user_type === 'student') {
        menuHTML += `
            <li><a href="#" onclick="navigateToSection('browse-courses')">
                <i class="fas fa-search"></i> Browse Courses
            </a></li>
            <li><a href="#" onclick="navigateToSection('my-courses')">
                <i class="fas fa-graduation-cap"></i> My Courses
            </a></li>
        `;
    }
    
    // Teacher specific menu
    if (currentUser.user_type === 'teacher') {
        menuHTML += `
            <li><a href="#" onclick="navigateToSection('create-class')">
                <i class="fas fa-plus"></i> Create Class
            </a></li>
            <li><a href="#" onclick="navigateToSection('my-classes')">
                <i class="fas fa-chalkboard-teacher"></i> My Classes
            </a></li>
            <li><a href="#" onclick="navigateToSection('online-lectures')">
                <i class="fas fa-video"></i> Online Lectures
            </a></li>
            <li><a href="#" onclick="navigateToSection('exams')">
                <i class="fas fa-clipboard-check"></i> Exams
            </a></li>
            <li><a href="#" onclick="navigateToSection('assignments')">
                <i class="fas fa-tasks"></i> Assignments
            </a></li>
            <li><a href="#" onclick="navigateToSection('lecture-notes')">
                <i class="fas fa-file-alt"></i> Lecture Notes
            </a></li>
            <li><a href="#" onclick="navigateToSection('students')">
                <i class="fas fa-users"></i> Students
            </a></li>
            <li><a href="#" onclick="navigateToSection('attendance')">
                <i class="fas fa-check-square"></i> Attendance
            </a></li>
        `;
    }
    
    // Admin specific menu (if needed in future)
    if (currentUser.user_type === 'admin') {
        menuHTML += `
            <li><a href="#" onclick="navigateToSection('manage-users')">
                <i class="fas fa-users-cog"></i> Manage Users
            </a></li>
            <li><a href="#" onclick="navigateToSection('system-settings')">
                <i class="fas fa-cogs"></i> System Settings
            </a></li>
        `;
    }
    
    sidebarMenu.innerHTML = menuHTML;
}

// Navigate to section
function navigateToSection(sectionName) {
    currentSection = sectionName;
    
    // Update active menu item
    const menuLinks = document.querySelectorAll('.sidebar-nav a');
    menuLinks.forEach(link => link.classList.remove('active'));
    event.target.classList.add('active');
    
    // Load section content
    loadSectionContent(sectionName);
}

// Load dashboard content
function loadDashboardContent() {
    loadSectionContent('dashboard');
}

// Load section content
function loadSectionContent(section) {
    const contentArea = document.getElementById('contentArea');
    
    switch(section) {
        case 'dashboard':
            contentArea.innerHTML = getDashboardContent();
            break;
        case 'profile':
            contentArea.innerHTML = getProfileContent();
            setupProfileForm();
            break;
        case 'browse-courses':
            contentArea.innerHTML = getBrowseCoursesContent();
            loadCourses();
            break;
        case 'my-courses':
            contentArea.innerHTML = getMyCoursesContent();
            loadMyCourses();
            break;
        case 'create-class':
            contentArea.innerHTML = getCreateClassContent();
            setupCreateClassForm();
            break;
        case 'my-classes':
            contentArea.innerHTML = getMyClassesContent();
            loadMyClasses();
            break;
        case 'online-lectures':
            contentArea.innerHTML = getOnlineLecturesContent();
            break;
        case 'exams':
            contentArea.innerHTML = getExamsContent();
            break;
        case 'assignments':
            contentArea.innerHTML = getAssignmentsContent();
            break;
        case 'lecture-notes':
            contentArea.innerHTML = getLectureNotesContent();
            break;
        case 'students':
            contentArea.innerHTML = getStudentsContent();
            loadStudents();
            break;
        case 'attendance':
            contentArea.innerHTML = getAttendanceContent();
            break;
        default:
            contentArea.innerHTML = getDashboardContent();
    }
}

// Dashboard content
function getDashboardContent() {
    const userType = currentUser.user_type;
    const greeting = getGreeting();
    
    return `
        <div class="content-header">
            <h1>${greeting}, ${currentUser.full_name}!</h1>
            <p>Welcome to your Learn.lk ${userType} dashboard</p>
        </div>
        
        <div class="content-section">
            <h2><i class="fas fa-chart-bar"></i> Dashboard Overview</h2>
            <div class="courses-grid">
                <div class="course-card">
                    <div class="course-image">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="course-content">
                        <h3>Learning Progress</h3>
                        <p>Track your educational journey</p>
                        <div class="course-footer">
                            <span>Active Courses</span>
                            <button class="btn btn-small btn-primary" onclick="navigateToSection('${userType === 'student' ? 'my-courses' : 'my-classes'}')">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="course-card">
                    <div class="course-image">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="course-content">
                        <h3>${userType === 'student' ? 'Browse Courses' : 'Manage Students'}</h3>
                        <p>${userType === 'student' ? 'Discover new learning opportunities' : 'Monitor student progress and attendance'}</p>
                        <div class="course-footer">
                            <span>${userType === 'student' ? 'Available' : 'Registered'}</span>
                            <button class="btn btn-small btn-primary" onclick="navigateToSection('${userType === 'student' ? 'browse-courses' : 'students'}')">
                                ${userType === 'student' ? 'Browse' : 'Manage'}
                            </button>
                        </div>
                    </div>
                </div>
                
                ${userType === 'teacher' ? `
                <div class="course-card">
                    <div class="course-image">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="course-content">
                        <h3>Online Lectures</h3>
                        <p>Conduct live classes and manage recordings</p>
                        <div class="course-footer">
                            <span>Live Sessions</span>
                            <button class="btn btn-small btn-primary" onclick="navigateToSection('online-lectures')">
                                Start Lecture
                            </button>
                        </div>
                    </div>
                </div>
                ` : ''}
            </div>
        </div>
    `;
}

// Profile content
function getProfileContent() {
    return `
        <div class="content-header">
            <h1><i class="fas fa-user"></i> User Profile</h1>
            <p>Manage your personal information and settings</p>
        </div>
        
        <div class="content-section">
            <div class="form-container">
                <form id="profileForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" name="fullName" value="${currentUser.full_name}" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" value="${currentUser.email}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Profile Photo URL</label>
                        <input type="url" name="photoUrl" value="${currentUser.photo_url || ''}" placeholder="https://example.com/photo.jpg">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user-tag"></i> User Type</label>
                        <input type="text" value="${currentUser.user_type}" readonly style="background: #f7fafc; cursor: not-allowed;">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>
    `;
}

// Get greeting based on time
function getGreeting() {
    const hour = new Date().getHours();
    if (hour < 12) return 'Good Morning';
    if (hour < 17) return 'Good Afternoon';
    return 'Good Evening';
}

// Get student sections
function getStudentSections() {
    return `
        <!-- Browse Courses Section -->
        <div id="courses-section" class="content-section">
            <h2>Browse Courses</h2>
            <div class="form-group">
                <input type="text" id="courseSearch" placeholder="Search courses..." onkeyup="searchCourses()">
            </div>
            <div id="coursesGrid" class="courses-grid">
                <!-- Courses will be loaded here -->
            </div>
        </div>
        
        <!-- My Courses Section -->
        <div id="my-courses-section" class="content-section">
            <h2>My Courses</h2>
            <div id="myCoursesGrid" class="courses-grid">
                <!-- My courses will be loaded here -->
            </div>
        </div>
    `;
}

// Get teacher sections
function getTeacherSections() {
    return `
        <!-- Create Class Section -->
        <div id="create-class-section" class="content-section">
            <h2>Create New Class</h2>
            <div class="form-container">
                <form id="createClassForm">
                    <div class="form-group">
                        <label for="className">Class Name:</label>
                        <input type="text" id="className" name="className" required>
                    </div>
                    <div class="form-group">
                        <label for="classDescription">Description:</label>
                        <textarea id="classDescription" name="description" rows="4" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="classPrice">Price (LKR):</label>
                            <input type="number" id="classPrice" name="price" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="classDuration">Duration (hours):</label>
                            <input type="number" id="classDuration" name="duration" min="1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="classSchedule">Schedule:</label>
                        <input type="datetime-local" id="classSchedule" name="schedule" required>
                    </div>
                    <button type="submit" class="btn-primary">Create Class</button>
                </form>
            </div>
        </div>
        
        <!-- My Classes Section -->
        <div id="my-classes-section" class="content-section">
            <h2>My Classes</h2>
            <div id="myClassesGrid" class="courses-grid">
                <!-- Teacher's classes will be loaded here -->
            </div>
        </div>
        
        <!-- Students Section -->
        <div id="students-section" class="content-section">
            <h2>Registered Students</h2>
            <div class="table-container">
                <table id="studentsTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Enrolled Courses</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Students will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

// Show section
function showSection(sectionName) {
    // Hide all sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Remove active class from all menu items
    const menuItems = document.querySelectorAll('.sidebar-menu a');
    menuItems.forEach(item => {
        item.classList.remove('active');
    });
    
    // Show selected section
    const targetSection = document.getElementById(sectionName + '-section');
    if (targetSection) {
        targetSection.classList.add('active');
    }
    
    // Add active class to clicked menu item
    event.target.classList.add('active');
    
    // Load section-specific data
    if (sectionName === 'courses') {
        loadCourses();
    } else if (sectionName === 'my-courses') {
        loadMyCourses();
    } else if (sectionName === 'my-classes') {
        loadTeacherClasses();
    } else if (sectionName === 'students') {
        loadStudents();
    }
}

// Setup profile form
function setupProfileForm() {
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const profileData = {
                fullName: formData.get('fullName'),
                email: formData.get('email'),
                photoUrl: formData.get('photoUrl')
            };
            
            try {
                const response = await fetch('php/profile.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'update_profile',
                        userId: currentUser.id,
                        ...profileData
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Update current user data
                    currentUser.fullName = profileData.fullName;
                    currentUser.email = profileData.email;
                    if (profileData.photoUrl) {
                        currentUser.photoUrl = profileData.photoUrl;
                    }
                    
                    // Update session storage
                    sessionStorage.setItem('user', JSON.stringify(currentUser));
                    
                    // Update UI
                    updateUserProfile();
                    
                    showNotification('Profile updated successfully!', 'success');
                } else {
                    showNotification(result.message || 'Failed to update profile', 'error');
                }
            } catch (error) {
                console.error('Profile update error:', error);
                showNotification('An error occurred while updating profile', 'error');
            }
        });
    }
}

// Update user profile in UI
function updateUserProfile() {
    const userAvatar = document.getElementById('userAvatar');
    if (currentUser.photoUrl) {
        userAvatar.innerHTML = `<img src="${currentUser.photoUrl}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
    } else {
        userAvatar.innerHTML = currentUser.fullName.charAt(0).toUpperCase();
    }
    
    // Update name in sidebar
    const profileSection = document.querySelector('.user-profile h3');
    if (profileSection) {
        profileSection.textContent = currentUser.fullName;
    }
}

// Load courses
async function loadCourses() {
    try {
        const response = await fetch('php/courses.php?action=get_all');
        const result = await response.json();
        
        if (result.success) {
            displayCourses(result.courses, 'coursesGrid');
        }
    } catch (error) {
        console.error('Error loading courses:', error);
    }
}

// Load my courses
async function loadMyCourses() {
    try {
        const response = await fetch(`php/courses.php?action=get_user_courses&userId=${currentUser.id}`);
        const result = await response.json();
        
        if (result.success) {
            displayMyCourses(result.courses, 'myCoursesGrid');
        }
    } catch (error) {
        console.error('Error loading my courses:', error);
    }
}

// Display courses
function displayCourses(courses, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    if (courses.length === 0) {
        container.innerHTML = '<p>No courses available.</p>';
        return;
    }
    
    let html = '';
    courses.forEach(course => {
        html += `
            <div class="course-card">
                <div class="course-image">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="course-content">
                    <h3 class="course-title">${course.name}</h3>
                    <p class="course-description">${course.description}</p>
                    <div class="course-price">LKR ${course.price}</div>
                    <div class="course-footer">
                        <span>By ${course.teacher_name}</span>
                        <button class="btn-small btn-enroll" onclick="enrollCourse(${course.id})">
                            Enroll Now
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Display my courses
function displayMyCourses(courses, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    if (courses.length === 0) {
        container.innerHTML = '<p>You haven\'t enrolled in any courses yet.</p>';
        return;
    }
    
    let html = '';
    courses.forEach(course => {
        html += `
            <div class="course-card">
                <div class="course-image">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="course-content">
                    <h3 class="course-title">${course.name}</h3>
                    <p class="course-description">${course.description}</p>
                    <div class="course-footer">
                        <span>Progress: ${course.progress || 0}%</span>
                        <button class="btn-small btn-enroll" onclick="accessCourse(${course.id})">
                            Access Course
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Enroll in course
async function enrollCourse(courseId) {
    try {
        const response = await fetch('php/enrollment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'enroll',
                userId: currentUser.id,
                courseId: courseId
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Successfully enrolled in course!', 'success');
            loadMyCourses(); // Refresh my courses
        } else {
            showNotification(result.message || 'Enrollment failed', 'error');
        }
    } catch (error) {
        console.error('Enrollment error:', error);
        showNotification('An error occurred during enrollment', 'error');
    }
}

// Search courses
function searchCourses() {
    const searchTerm = document.getElementById('courseSearch').value;
    // Implement search functionality
    console.log('Searching for:', searchTerm);
}

// Load teacher classes
async function loadTeacherClasses() {
    try {
        const response = await fetch(`php/courses.php?action=get_teacher_courses&teacherId=${currentUser.id}`);
        const result = await response.json();
        
        if (result.success) {
            displayTeacherClasses(result.courses, 'myClassesGrid');
        }
    } catch (error) {
        console.error('Error loading teacher classes:', error);
    }
}

// Display teacher classes
function displayTeacherClasses(classes, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    if (classes.length === 0) {
        container.innerHTML = '<p>You haven\'t created any classes yet.</p>';
        return;
    }
    
    let html = '';
    classes.forEach(classItem => {
        html += `
            <div class="course-card">
                <div class="course-image">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="course-content">
                    <h3 class="course-title">${classItem.name}</h3>
                    <p class="course-description">${classItem.description}</p>
                    <div class="course-price">LKR ${classItem.price}</div>
                    <div class="course-footer">
                        <span>${classItem.enrolled_count || 0} students</span>
                        <button class="btn-small btn-enroll" onclick="manageClass(${classItem.id})">
                            Manage
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

// Logout
function logout() {
    sessionStorage.removeItem('user');
    sessionStorage.removeItem('isLoggedIn');
    location.reload();
}

// Access course (placeholder)
function accessCourse(courseId) {
    showNotification('Course access feature coming soon!', 'info');
}

// Manage class (placeholder)
function manageClass(classId) {
    showNotification('Class management feature coming soon!', 'info');
}

// Browse courses content
function getBrowseCoursesContent() {
    return `
        <div class="content-header">
            <h1><i class="fas fa-search"></i> Browse Courses</h1>
            <p>Discover and enroll in new courses</p>
        </div>
        
        <div class="content-section">
            <div class="form-group" style="margin-bottom: 2rem;">
                <input type="text" id="courseSearch" placeholder="🔍 Search courses..." onkeyup="searchCourses()" style="max-width: 400px;">
            </div>
            <div id="coursesGrid" class="courses-grid">
                <div class="course-card">
                    <div class="course-image"><i class="fas fa-spinner fa-spin"></i></div>
                    <div class="course-content">
                        <h3>Loading courses...</h3>
                        <p>Please wait while we fetch available courses</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// My courses content
function getMyCoursesContent() {
    return `
        <div class="content-header">
            <h1><i class="fas fa-graduation-cap"></i> My Courses</h1>
            <p>Access your enrolled courses and track progress</p>
        </div>
        
        <div class="content-section">
            <div id="myCoursesGrid" class="courses-grid">
                <div class="course-card">
                    <div class="course-image"><i class="fas fa-spinner fa-spin"></i></div>
                    <div class="course-content">
                        <h3>Loading your courses...</h3>
                        <p>Please wait while we fetch your enrolled courses</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Create class content
function getCreateClassContent() {
    return `
        <div class="content-header">
            <h1><i class="fas fa-plus"></i> Create New Class</h1>
            <p>Set up a new course for your students</p>
        </div>
        
        <div class="content-section">
            <div class="form-container">
                <form id="createClassForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-book"></i> Course Name</label>
                            <input type="text" name="name" required placeholder="e.g., Introduction to Web Development">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-money-bill"></i> Price (LKR)</label>
                            <input type="number" name="price" min="0" required placeholder="5000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Description</label>
                        <textarea name="description" rows="4" required placeholder="Detailed course description..."></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-clock"></i> Duration (Hours)</label>
                            <input type="number" name="duration" min="1" required placeholder="40">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-calendar"></i> Start Date & Time</label>
                            <input type="datetime-local" name="schedule">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Class
                    </button>
                </form>
            </div>
        </div>
    `;
}

// My classes content
function getMyClassesContent() {
    return `
        <div class="content-header">
            <h1><i class="fas fa-chalkboard-teacher"></i> My Classes</h1>
            <p>Manage your courses and student progress</p>
        </div>
        
        <div class="content-section">
            <div id="myClassesGrid" class="courses-grid">
                <div class="course-card">
                    <div class="course-image"><i class="fas fa-spinner fa-spin"></i></div>
                    <div class="course-content">
                        <h3>Loading your classes...</h3>
                        <p>Please wait while we fetch your created classes</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Online lectures content
function getOnlineLecturesContent() {
    return `
        <div class="content-header">
            <h1><i class="fas fa-video"></i> Online Lectures</h1>
            <p>Conduct live classes and manage recordings</p>
        </div>
        
        <div class="content-section">
            <div class="courses-grid">
                <div class="course-card">
                    <div class="course-image">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
                    <div class="course-content">
                        <h3>Start Live Lecture</h3>
                        <p>Begin a live session with your students</p>
                        <div class="course-footer">
                            <span>Live Stream</span>
                            <button class="btn btn-small btn-primary" onclick="startLiveLecture()">
                                <i class="fas fa-play"></i> Go Live
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="course-card">
                    <div class="course-image">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="course-content">
                        <h3>Recorded Lectures</h3>
                        <p>View and manage your lecture recordings</p>
                        <div class="course-footer">
                            <span>Archive</span>
                            <button class="btn btn-small btn-outline" onclick="showNotification('Recordings feature coming soon!', 'info')">
                                View Recordings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Students content
function getStudentsContent() {
    return `
        <div class="content-header">
            <h1><i class="fas fa-users"></i> Registered Students</h1>
            <p>Monitor and manage your students</p>
        </div>
        
        <div class="content-section">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Enrolled Courses</th>
                            <th>Progress</th>
                            <th>Last Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">
                                <i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>
                                Loading students...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

// Notification system
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 4000);
}

// Logout function
function logout() {
    sessionStorage.removeItem('user');
    sessionStorage.removeItem('isLoggedIn');
    currentUser = null;
    isLoggedIn = false;
    
    showNotification('Logged out successfully', 'success');
    
    // Redirect to home page
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Load courses function
async function loadCourses() {
    try {
        const response = await fetch('php/courses.php?action=get_all');
        const result = await response.json();
        
        if (result.success && result.data && result.data.courses) {
            displayCourses(result.data.courses);
        } else {
            displayNoCourses();
        }
    } catch (error) {
        console.error('Error loading courses:', error);
        displayCoursesError();
    }
}

// Display courses
function displayCourses(courses) {
    const container = document.getElementById('coursesGrid');
    if (!container) return;
    
    if (courses.length === 0) {
        displayNoCourses();
        return;
    }
    
    let html = '';
    courses.forEach(course => {
        html += `
            <div class="course-card">
                <div class="course-image">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="course-content">
                    <h3 class="course-title">${course.name}</h3>
                    <p class="course-description">${course.description}</p>
                    <div class="course-price">LKR ${parseFloat(course.price).toLocaleString()}</div>
                    <div class="course-footer">
                        <span>By ${course.teacher_name || 'Instructor'}</span>
                        <button class="btn btn-small btn-enroll" onclick="enrollInCourse(${course.id})">
                            <i class="fas fa-plus"></i> Enroll
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Display no courses message
function displayNoCourses() {
    const container = document.getElementById('coursesGrid');
    if (container) {
        container.innerHTML = `
            <div class="course-card">
                <div class="course-image">
                    <i class="fas fa-book"></i>
                </div>
                <div class="course-content">
                    <h3>No Courses Available</h3>
                    <p>Check back later for new courses!</p>
                </div>
            </div>
        `;
    }
}

// Display courses error
function displayCoursesError() {
    const container = document.getElementById('coursesGrid');
    if (container) {
        container.innerHTML = `
            <div class="course-card">
                <div class="course-image">
                    <i class="fas fa-exclamation-triangle" style="color: #f56565;"></i>
                </div>
                <div class="course-content">
                    <h3>Error Loading Courses</h3>
                    <p>Please refresh the page and try again</p>
                    <button class="btn btn-small btn-primary" onclick="loadCourses()">
                        <i class="fas fa-refresh"></i> Retry
                    </button>
                </div>
            </div>
        `;
    }
}

// Enroll in course
async function enrollInCourse(courseId) {
    try {
        const response = await fetch('php/enrollment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'enroll',
                userId: currentUser.id,
                courseId: courseId
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Successfully enrolled! Payment processing...', 'success');
            // In a real app, this would redirect to payment gateway
            setTimeout(() => {
                showNotification('Payment completed successfully!', 'success');
            }, 2000);
        } else {
            showNotification(result.message || 'Enrollment failed', 'error');
        }
    } catch (error) {
        console.error('Enrollment error:', error);
        showNotification('Enrollment failed. Please try again.', 'error');
    }
}

// Placeholder functions for features
function loadMyCourses() {
    const container = document.getElementById('myCoursesGrid');
    if (container) {
        container.innerHTML = `
            <div class="course-card">
                <div class="course-image">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="course-content">
                    <h3>No Enrolled Courses</h3>
                    <p>Browse courses to start learning!</p>
                    <div class="course-footer">
                        <button class="btn btn-small btn-primary" onclick="navigateToSection('browse-courses')">
                            Browse Courses
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
}

function loadMyClasses() {
    const container = document.getElementById('myClassesGrid');
    if (container) {
        container.innerHTML = `
            <div class="course-card">
                <div class="course-image">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="course-content">
                    <h3>Create Your First Class</h3>
                    <p>Start teaching by creating a new course</p>
                    <div class="course-footer">
                        <button class="btn btn-small btn-primary" onclick="navigateToSection('create-class')">
                            Create Class
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
}

function loadStudents() {
    const tableBody = document.getElementById('studentsTableBody');
    if (tableBody) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: #718096;">
                    <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                    No students enrolled yet
                </td>
            </tr>
        `;
    }
}