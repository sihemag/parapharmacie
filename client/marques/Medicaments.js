// Array to store selected categories
let selectedCategories = [];
let selectedSubCategories = [];
let searchTerm = '';
// Add event listeners to checkboxes
const categoryCheckboxes = document.querySelectorAll('.categoryCheckbox');
const subCategoryCheckboxes = document.querySelectorAll('.subcategoryCheckbox');

categoryCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        // Get the label associated with the checkbox
        const label = this.parentElement.querySelector('label');
        // Extract the category name from the label text content
        const categoryName = label.textContent.trim().toLowerCase();
        const image = checkbox.nextElementSibling.querySelector('.ImageSvgCheck');


        // Toggle the display of subcategory containers
        const categoryId = this.id;
        const subcategoryContainers = document.querySelectorAll('.subcategory_' + categoryId);
        
        subcategoryContainers.forEach(container => {
            
            container.style.display = this.checked ? 'flex' : 'none';
        });

        // Update the selectedCategories array
        if (this.checked) {
            selectedCategories.push(categoryName);
            image.src = '../Image/arrow-to-top-right-svgrepo-com.svg';
        } else {
            selectedCategories = selectedCategories.filter(cat => cat !== categoryName);
            image.src = '../Image/arrow-to-down-right-svgrepo-com.svg';
        }

        // Call filterMedications function
        filterMedications();
    });
});


subCategoryCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
       // Get the label associated with the checkbox
        const label = this.parentElement.querySelector('label');
        // Extract the category name from the label text content
        const subCategoryName = label.textContent.trim().toLowerCase();
        
        if (this.checked) {
            selectedSubCategories.push(subCategoryName);
        } else {
            selectedSubCategories = selectedSubCategories.filter(cat => cat !== subCategoryName);
        }
        filterMedications();
    });
});

// Filter medications based on search input
document.getElementById('searchInput').addEventListener('input', function() {
    searchTerm = this.value.trim().toLowerCase();
    filterMedications();
});

if(document.getElementById('searchInput').value != ''){
    searchTerm=document.getElementById('searchInput').value;
    filterMedications();
}

// Function to filter medications based on selected categories and search term
function filterMedications() {
    const rightBoxes = document.querySelectorAll('.RightBox');
    rightBoxes.forEach(box => {
        
        const medicationName = box.querySelector('h3').textContent.toLowerCase().trim();
        const boxCategory = box.dataset.category.toLowerCase();
        const boxSubCategory = box.dataset.subcategory.toLowerCase();
        if (selectedCategories.length === 0) {
            // If no checkboxes are selected, display all boxes
            if (searchTerm !== '') {
                // If there is something in the text input, check if it matches the medication name
                const regex = new RegExp('^' + searchTerm.toLowerCase().trim());
                if (regex.test(medicationName)) {
                    box.style.display = '';
                } else {
                    box.style.display = 'none';
                }
            } else {
                // If there is nothing in the text input, display the box
                box.style.display = '';
            }
        } else {
            // If checkboxes are selected, filter based on categories
            if (selectedCategories.includes(boxCategory) || selectedSubCategories.includes(boxSubCategory)) {
                // If the box category matches one of the selected categories
                
                    // check if it include any subCategory
                    if (searchTerm !== '') {
                        // If there is something in the text input, check both category and medication name
                        const regex = new RegExp('^' + searchTerm.toLowerCase().trim());
                        if (regex.test(medicationName)) {
                            box.style.display = '';
                        } else {
                            box.style.display = 'none';
                        }
                    } else {
                        // If there is nothing in the text input, display the box
                        box.style.display = '';
                    }
                   
            } else {
                // If the box category does not match any selected category, hide the box
                box.style.display = 'none';
            }
        }
    });
}