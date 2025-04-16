<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Movie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8 px-4">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 bg-gray-800 text-white">
                <h1 class="text-2xl font-bold">Add New Movie</h1>
            </div>
            
            <form id="movieForm" class="p-6 space-y-6" enctype="multipart/form-data">
                <!-- Success/Error Messages -->
                <div id="messageContainer" class="hidden"></div>
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title*</label>
                    <input type="text" id="title" name="title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span id="title-error" class="text-red-500 text-xs"></span>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description*</label>
                    <textarea id="description" name="description" rows="4" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    <span id="description-error" class="text-red-500 text-xs"></span>
                </div>
                
                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Poster Image*</label>
                    <div class="flex items-center space-x-4">
                        <div class="shrink-0">
                            <img id="imagePreview" class="h-32 w-24 object-cover rounded-md border border-gray-300 hidden" src="#" alt="Preview">
                        </div>
                        <div class="flex-1">
                            <input type="file" id="image" name="image" accept="image/*" required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <span id="image-error" class="text-red-500 text-xs"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)*</label>
                    <input type="number" id="duration" name="duration" min="1" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span id="duration-error" class="text-red-500 text-xs"></span>
                </div>
                
                <!-- Minimum Age -->
                <div>
                    <label for="minimum_age" class="block text-sm font-medium text-gray-700 mb-1">Minimum Age*</label>
                    <select id="minimum_age" name="minimum_age" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select age rating</option>
                        <option value="0">All ages</option>
                        <option value="6">6+</option>
                        <option value="12">12+</option>
                        <option value="16">16+</option>
                        <option value="18">18+</option>
                    </select>
                    <span id="minimum_age-error" class="text-red-500 text-xs"></span>
                </div>
                
                <!-- Trailer URL -->
                <div>
                    <label for="trailer" class="block text-sm font-medium text-gray-700 mb-1">Trailer URL*</label>
                    <input type="url" id="trailer" name="trailer" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span id="trailer-error" class="text-red-500 text-xs"></span>
                </div>
                
                <!-- Genre -->
                <div>
                    <label for="genre" class="block text-sm font-medium text-gray-700 mb-1">Genre*</label>
                    <input type="text" id="genre" name="genre" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span id="genre-error" class="text-red-500 text-xs"></span>
                </div>
                
                <!-- Actors -->
                <div>
                    <label for="actors" class="block text-sm font-medium text-gray-700 mb-1">Actors* (comma separated)</label>
                    <input type="text" id="actors" name="actors" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span id="actors-error" class="text-red-500 text-xs"></span>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="window.location.href='/movies'"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Movie
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('imagePreview');
                    preview.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission
        document.getElementById('movieForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Clear previous errors
            document.querySelectorAll('[id$="-error"]').forEach(el => el.textContent = '');
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.classList.add('hidden');
            
            // Prepare form data
            const formData = new FormData();
            formData.append('title', document.getElementById('title').value);
            formData.append('description', document.getElementById('description').value);
            formData.append('image', document.getElementById('image').files[0]);
            formData.append('duration', document.getElementById('duration').value);
            formData.append('minimum_age', document.getElementById('minimum_age').value);
            formData.append('trailer', document.getElementById('trailer').value);
            formData.append('genre', document.getElementById('genre').value);
            formData.append('actors', document.getElementById('actors').value);
            
            // Get auth token
            const token = localStorage.getItem('auth_token');
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
            
            try {
                const response = await fetch('/api/movies', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    // Handle validation errors
                    if (data.errors) {
                        for (const [field, message] of Object.entries(data.errors)) {
                            const errorElement = document.getElementById(`${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = message;
                            }
                        }
                    } else {
                        showMessage(data.message || 'Failed to create movie', 'error');
                    }
                } else {
                    showMessage('Movie created successfully! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = '/movies';
                    }, 1500);
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('An error occurred while creating the movie', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save Movie';
            }
        });
        
        function showMessage(message, type) {
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.textContent = message;
            messageContainer.className = `p-4 rounded-md ${type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`;
            messageContainer.classList.remove('hidden');
        }
    </script>
</body>
</html>