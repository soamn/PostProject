
var postId;
const appurl="http://127.0.0.1:8002";
(function() {
  const script = document.currentScript;
  if (script) {
    const urlParams = new URLSearchParams(new URL(script.src).search);
    postId = urlParams.get('id');
    console.log('Post ID:', postId);
  }
})();

document.addEventListener('DOMContentLoaded', () => {
  if (!postId) {
    console.error('Post ID is required to fetch the post');
    return;
  }

  const apiUrl = `${appurl}/api/posts/${postId}`;

  fetch(apiUrl)
    .then(response => response.json())
    .then(post => {
      const postContainer = document.getElementById(`pc-${postId}`);
      if (!postContainer) return;

      if (post.error) {
        postContainer.innerHTML = `<p>${post.error}</p>`;
      } else {
        const postTitle = document.createElement('h2');
        postTitle.classList.add('post-title');
        postTitle.textContent = post.title ?? "No post";
        postContainer.appendChild(postTitle);

        const postDescription = document.createElement('div');
        postDescription.classList.add('post-description');
        postDescription.innerHTML = post.description ?? "Thanks for viewing";
        postContainer.appendChild(postDescription);

        const postDate = document.createElement('span');
        postDate.classList.add('post-published-date');
        postDate.textContent = `Published on: ${new Date(post.published_at).toLocaleDateString('en-US', {
          month: 'long',
          day: 'numeric',
          year: 'numeric'
        })}`;
        postContainer.appendChild(postDate);
      }
    })
    .catch(error => {
      console.error('Error fetching post:', error);
    });
});
