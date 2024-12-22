// document.addEventListener('DOMContentLoaded', function() {
//     const newsForm = document.getElementById('addNewsForm');
//     const newsList = document.getElementById('newsList');

//     function showNewsForm() {
//         document.getElementById('newsForm').style.display = 'block';
//     }

//     function hideNewsForm() {
//         document.getElementById('newsForm').style.display = 'none';
//         newsForm.reset();
//     }

//     function showNotification(message, type = 'success') {
//         const notification = document.getElementById('notification');
//         notification.className = `notification ${type}`;
//         notification.querySelector('.notification-message').textContent = message;
//         notification.style.display = 'block';

//         // Auto hide after 5 seconds
//         setTimeout(hideNotification, 5000);
//     }

//     function hideNotification() {
//         const notification = document.getElementById('notification');
//         notification.style.display = 'none';
//     }

//     function toggleMediaInput() {
//         const mediaType = document.getElementById('mediaType').value;
//         const mediaFile = document.getElementById('mediaFile');
        
//         if (mediaType === 'video') {
//             mediaFile.accept = 'video/*';
//         } else {
//             mediaFile.accept = 'image/*';
//         }
//     }

//     async function handleFormSubmit(event) {
//         event.preventDefault();
        
//         const formData = new FormData(event.target);
//         const mediaFile = formData.get('mediaFile');
//         const mediaUrl = formData.get('mediaUrl');

//         if (!mediaFile && !mediaUrl) {
//             showNotification('Please provide either a file or URL for the media', 'error');
//             return;
//         }

//         try {
//             // Create form data for submission
//             const submitData = new FormData();
//             submitData.append('title', formData.get('title'));
//             submitData.append('content', formData.get('content'));
//             submitData.append('category', formData.get('category'));
//             submitData.append('mediaType', formData.get('mediaType'));
//             submitData.append('keywords', formData.get('keywords'));

//             // Add media file or URL
//             if (mediaFile.size > 0) {
//                 submitData.append('mediaFile', mediaFile);
//             } else {
//                 submitData.append('mediaUrl', mediaUrl);
//             }

//             const response = await fetch('submit_article.php', {
//                 method: 'POST',
//                 body: submitData
//             });

//             const result = await response.json();

//             if (result.status === 'success') {
//                 showNotification('Article posted successfully!');
//                 hideNewsForm();
//                 fetchNewsList();
//             } else {
//                 throw new Error(result.message || 'Failed to post article');
//             }
//         } catch (error) {
//             console.error('Error:', error);
//             showNotification(`Failed to post article: ${error.message}`, 'error');
//         }
//     }

//     async function fetchNewsList() {
//         try {
//             const response = await fetch('submit_article.php');
//             const result = await response.json();

//             if (result.status === 'success') {
//                 displayNewsList(result.data);
//             } else {
//                 throw new Error(result.message || 'Failed to fetch articles');
//             }
//         } catch (error) {
//             console.error('Error fetching news:', error);
//             showNotification('Failed to fetch articles', 'error');
//         }
//     }

//     function displayNewsList(articles) {
//         newsList.innerHTML = articles.map(article => `
//             <div class="news-item">
//                 <div class="news-content">
//                     <h3>${article.title}</h3>
//                     <p class="category">${article.category}</p>
//                     <p class="timestamp">${new Date(article.created_at).toLocaleString()}</p>
//                 </div>
//                 <div class="news-actions">
//                     <button onclick="editArticle(${article.id})" class="edit-btn">
//                         <i class="fas fa-edit"></i> Edit
//                     </button>
//                     <button onclick="deleteArticle(${article.id})" class="delete-btn">
//                         <i class="fas fa-trash"></i> Delete
//                     </button>
//                 </div>
//             </div>
//         `).join('');
//     }

//     async function deleteArticle(id) {
//         if (!confirm('Are you sure you want to delete this article?')) {
//             return;
//         }

//         try {
//             const response = await fetch(`submit_article.php?id=${id}`, {
//                 method: 'DELETE'
//             });
//             const result = await response.json();

//             if (result.status === 'success') {
//                 showNotification('Article deleted successfully');
//                 fetchNewsList();
//             } else {
//                 throw new Error(result.message || 'Failed to delete article');
//             }
//         } catch (error) {
//             console.error('Error:', error);
//             showNotification(`Failed to delete article: ${error.message}`, 'error');
//         }
//     }

//     // Add event listeners
//     document.getElementById('addNewsForm').addEventListener('submit', handleFormSubmit);
//     document.getElementById('mediaType').addEventListener('change', toggleMediaInput);

//     // Initial news list fetch
//     fetchNewsList();

//     // Make functions globally available
//     window.showNewsForm = showNewsForm;
//     window.hideNewsForm = hideNewsForm;
//     window.deleteArticle = deleteArticle;
// });