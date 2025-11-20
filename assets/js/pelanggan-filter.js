/**
 * Pelanggan Filter JavaScript
 * Handles region filtering for branches/customers page
 */

document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-tab');
    const pelangganCards = document.querySelectorAll('.pelanggan-card');

    if (!filterButtons.length || !pelangganCards.length) {
        return;
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get filter value
            const filterValue = this.getAttribute('data-filter');
            
            // Filter cards
            pelangganCards.forEach(card => {
                const cardRegion = card.getAttribute('data-region');
                
                if (filterValue === 'all') {
                    // Show all cards
                    card.style.display = 'block';
                    // Add animation
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else if (cardRegion === filterValue) {
                    // Show matching cards
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    // Hide non-matching cards
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });

            // Count visible cards
            setTimeout(() => {
                const visibleCards = Array.from(pelangganCards).filter(card => 
                    card.style.display !== 'none'
                );
                
                // Show "no results" message if needed
                const pelangganGrid = document.querySelector('.pelanggan-grid');
                let noResultsDiv = pelangganGrid.querySelector('.no-results-filter');
                
                if (visibleCards.length === 0) {
                    if (!noResultsDiv) {
                        noResultsDiv = document.createElement('div');
                        noResultsDiv.className = 'no-results-filter';
                        noResultsDiv.style.cssText = 'grid-column: 1 / -1; text-align: center; padding: 40px; color: #666;';
                        noResultsDiv.innerHTML = '<p>Tidak ada cabang di daerah ini.</p>';
                        pelangganGrid.appendChild(noResultsDiv);
                    }
                } else {
                    if (noResultsDiv) {
                        noResultsDiv.remove();
                    }
                }
            }, 350);
        });
    });

    // Add smooth transition to cards
    pelangganCards.forEach(card => {
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    });
});
