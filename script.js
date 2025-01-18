document.addEventListener('DOMContentLoaded', () => {
    const currentPage = document.body.dataset.page;

    const apiBaseUrl = 'http://localhost/IDS%20Community%20Sharing%20Platform/api/';

    // Validate user session
    let loggedInUserID = null;

    const validateSession = async () => {
        try {
            const response = await fetch(`${apiBaseUrl}login_logout.php?action=validate`);
            const data = await response.json();
    
            if (data.isLoggedIn) {
                loggedInUserID = data.user.ID; 
                console.log('UserID:', loggedInUserID);
                const loginLink = document.getElementById('login-link');
                const logoutLink = document.getElementById('logout-link');
    
                if (loginLink) loginLink.style.display = 'none';
                if (logoutLink) logoutLink.style.display = 'block';
            } else {
                const loginLink = document.getElementById('login-link');
                const logoutLink = document.getElementById('logout-link');
    
                if (loginLink) loginLink.style.display = 'block';
                if (logoutLink) logoutLink.style.display = 'none';
    
                if (currentPage !== 'login' && currentPage !== 'register') {
                    window.location.href = 'login.html';
                }
            }
        } catch (error) {
            console.error('Error validating session:', error);
        }
    };
        

    // login
    const initializeLoginPage = () => {
        const loginForm = document.getElementById('login-form');

        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const email = document.getElementById('login-email').value.trim();
            const password = document.getElementById('login-password').value.trim();

            try {
                const response = await fetch(`${apiBaseUrl}login_logout.php?action=login`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ Email: email, Password: password }),
                });

                const data = await response.json();

                if (data.user) {
                    alert('Login successful!');
                    window.location.href = 'posts.html';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Error during login:', error);
                alert('An error occurred while logging in. Please try again.');
            }
        });
    };

    // logout
    const handleLogout = async () => {
        try {
            const response = await fetch(`${apiBaseUrl}login_logout.php?action=logout`, {
                method: 'POST',
            });

            if (response.ok) {
                alert('Logged out successfully');
                window.location.href = 'login.html';
            } else {
                throw new Error('Failed to log out');
            }
        } catch (error) {
            console.error('Error logging out:', error);
        }
    };

    const logoutLink = document.getElementById('logout-link');
    if (logoutLink) logoutLink.addEventListener('click', handleLogout);

    validateSession();

    // Post Details Page
    const initializePostDetailsPage = () => {
        const postId = new URLSearchParams(window.location.search).get('id');

        const fetchPostDetails = async (id) => {
            try {
                const response = await fetch(`http://example.com/api/posts/${id}`);
                const post = await response.json();

                
                document.getElementById('post-title').textContent = post.title;
                document.getElementById('post-author').textContent = post.author;
                document.getElementById('post-date').textContent = post.date;
                document.getElementById('post-content').innerHTML = post.content;
                document.getElementById('post-image').src = post.ImageURL || 'images/default-image.png'; 

                
                const tagsContainer = document.getElementById('post-tags');
                tagsContainer.innerHTML = '';
                post.tags.forEach(tag => {
                    const tagElement = document.createElement('span');
                    tagElement.classList.add('tag');
                    tagElement.textContent = `#${tag}`;
                    tagsContainer.appendChild(tagElement);
                });
            } catch (error) {
                console.error('Error fetching post details:', error);
            }
        };

        const fetchComments = async (id) => {
            try {
                const response = await fetch(`http://example.com/api/posts/${id}/comments`);
                const comments = await response.json();

                const commentsSection = document.getElementById('comments-section');
                commentsSection.innerHTML = '';
                comments.forEach(comment => {
                    const commentElement = document.createElement('div');
                    commentElement.classList.add('comment');
                    commentElement.innerHTML = `<strong>${comment.author}:</strong> <p>${comment.text}</p>`;
                    commentsSection.appendChild(commentElement);
                });
            } catch (error) {
                console.error('Error fetching comments:', error);
            }
        };

        const handleCommentSubmission = async (e) => {
            e.preventDefault();
            const commentInput = document.getElementById('comment-input');
            const commentText = commentInput.value.trim();

            if (commentText) {
                try {
                    await fetch(`http://example.com/api/posts/${postId}/comments`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ text: commentText }),
                    });

                    fetchComments(postId);
                    commentInput.value = '';
                } catch (error) {
                    console.error('Error submitting comment:', error);
                }
            } else {
                alert('Please write a comment before submitting.');
            }
        };

        if (postId) {
            fetchPostDetails(postId);
            fetchComments(postId);

            document.getElementById('comment-form').addEventListener('submit', handleCommentSubmission);
        } else {
            console.error('Post ID not found in URL');
        }
    };

    const initializeCreatePostPage = () => {
        const createPostForm = document.getElementById('create-post-form');
        const categorySelect = document.getElementById('post-category');

        const fetchCategories = async () => {
            try {
                const response = await fetch(`${apiBaseUrl}Category_api.php`);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const categories = await response.json();
                console.log('Fetched Categories:', categories); 

                const categoryDropdown = document.getElementById('post-category');
                categoryDropdown.innerHTML = '<option value="" disabled selected>Select a category</option>'; 

                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.ID; 
                    option.textContent = category.Name; 
                    categoryDropdown.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching categories:', error);
                alert('Failed to load categories. Please try again.');
            }
        };

        fetchCategories();

        createPostForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(createPostForm);
            formData.append('UserID', loggedInUserID);

            try {
                const response = await fetch('http://localhost/IDS%20Community%20Sharing%20Platform/api/Post_api.php', {
                    method: 'POST',
                    body: formData,
                });

                const responseText = await response.text(); 
                console.log('Server Response:', responseText);

                const data = JSON.parse(responseText);
                if (response.ok) {
                    alert('Post created successfully!');
                    createPostForm.reset();
                    window.location.href = 'posts.html';
                } else {
                    alert(data.message || 'Failed to create post.');
                }
            } catch (error) {
                console.error('Error submitting post:', error);
                alert('An error occurred. Please try again.');
            }
        });
    };

    
    
    
    const initializeProfilePage = () => {
        const fetchUserProfile = async () => {
            try {
                const response = await fetch('http://example.com/api/profile'); 
                const user = await response.json();

                document.getElementById('profile-avatar').src = user.avatar || 'images/default-avatar.png';
                document.getElementById('profile-name').textContent = user.name;
                document.getElementById('profile-email').textContent = user.email;
                document.getElementById('total-posts').textContent = user.totalPosts || 0;
                document.getElementById('total-comments').textContent = user.totalComments || 0;
                document.getElementById('total-upvotes').textContent = user.totalUpvotes || 0;

                const postsContainer = document.getElementById('user-posts');
                user.posts.forEach(post => {
                    const postElement = document.createElement('div');
                    postElement.classList.add('col-md-4');
                    postElement.innerHTML = `
                        <div class="post">
                            <img src="${post.image}" alt="Post Image" class="img-fluid">
                            <h4>${post.title}</h4>
                            <p>${post.excerpt}</p>
                            <a href="post-details.html?id=${post.id}" class="btn btn-link">Read More</a>
                        </div>
                    `;
                    postsContainer.appendChild(postElement);
                });
            } catch (error) {
                console.error('Error fetching user profile:', error);
            }
        };

        fetchUserProfile();
    };

    // Registration Page
    const initializeRegisterPage = () => {
        const registerForm = document.getElementById('register-form');

        registerForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const name = document.getElementById('register-name').value.trim();
            const email = document.getElementById('register-email').value.trim();
            const password = document.getElementById('register-password').value.trim();

            if (name && email && password) {
                try {
                    const response = await fetch(`${apiBaseUrl}account_api.php?action=register`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ UserName: name, Email: email, Password: password }),
                    });

                    const data = await response.json();

                    if (response.ok && data.message === 'Registration successful') {
                        alert('Registration successful! Please login.');
                        window.location.href = 'login.html';
                    } else {
                        alert(data.message);
                    }
                } catch (error) {
                    console.error('Error during registration:', error);
                    alert('An error occurred during registration. Please try again.');
                }
            } else {
                alert('Please fill in all fields.');
            }
        });
    };

     // Posts Page
     const initializePostsPage = () => {
        const postsContainer = document.getElementById('post-grid');
        const searchInput = document.getElementById('search-input');
        const modal = document.getElementById('post-modal');
        const modalContent = document.getElementById('modal-content');
        const modalImage = document.getElementById('modal-image');
        const modalTitle = document.getElementById('modal-title');
        const modalAuthor = document.getElementById('modal-author');
        const modalCloseButton = document.getElementById('close-modal');
    
        const fetchPosts = async (query = '') => {
            try {
                const response = await fetch(`${apiBaseUrl}Post_api.php?search=${encodeURIComponent(query)}`);
                const posts = await response.json();
                console.log('Posts:', posts);
    
                postsContainer.innerHTML = ''; 
    
                if (posts.length === 0) {
                    postsContainer.innerHTML = '<p>No posts found.</p>';
                    return;
                }
    
                posts.forEach(post => {
                    const postElement = document.createElement('div');
                    postElement.classList.add('post-card');
                    postElement.innerHTML = `
                        <img src="${post.ImageURL || 'default-image.png'}" alt="${post.Title}" />
                        <h4>${post.Title}</h4>
                        <p>${post.Description.substring(0, 100)}...</p>
                        <p>By: ${post.UserName}</p>
                        <button class="view-post" data-id="${post.ID}">View</button>
                    `;
                    postsContainer.appendChild(postElement);
                });
    
              
                const viewButtons = document.querySelectorAll('.view-post');
                viewButtons.forEach(button => {
                    button.addEventListener('click', (e) => {
                        const postId = e.target.getAttribute('data-id');
                        openModal(postId);
                    });
                });
            } catch (error) {
                console.error('Error fetching posts:', error);
                postsContainer.innerHTML = '<p>Error loading posts. Please try again later.</p>';
            }
        };
    
        // Open modal
        const openModal = async (postId) => {
            try {
                const response = await fetch(`${apiBaseUrl}Post_api.php?id=${postId}`);
                const post = await response.json();
    
                modalImage.src = post.ImageURL || 'images/default-image.png';
                modalTitle.textContent = post.Title;
                modalContent.textContent = post.Description;
                modalAuthor.textContent = `By: ${post.UserName}`;
    
                modal.style.display = 'flex';
            } catch (error) {
                console.error('Error fetching post details:', error);
            }
        };
    
        // Close modal
        const closeModal = () => {
            modal.style.display = 'none';
        };
    
        modalCloseButton.addEventListener('click', closeModal);
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    
        // Search
        searchInput.addEventListener('input', (event) => {
            const query = event.target.value.trim();
            fetchPosts(query); 
        });

        fetchPosts();
    };
    
    

    if (currentPage === 'post-details') {
        initializePostDetailsPage();
    } else if (currentPage === 'profile') {
        initializeProfilePage();
    } else if (currentPage === 'create-post') {
        initializeCreatePostPage();
    } else if (currentPage === 'login') {
        initializeLoginPage();
    } else if (currentPage === 'register') {
        initializeRegisterPage();
    } else if (currentPage === 'posts') {
        initializePostsPage();
    }
});
