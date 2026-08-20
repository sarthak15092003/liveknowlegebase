document.addEventListener('DOMContentLoaded', function() {
    // Handle expandable sections
    document.addEventListener('click', function(e) {
        // 1. Top-Level Category Toggle
        const header = e.target.closest('.section-header.expandable');
        if (header) {
            if (e.target.closest('a') && !e.target.closest('.expand-icon')) return;

            e.preventDefault();
            const section = header.closest('.sidebar-section');
            const content = section ? section.querySelector('.section-content') : null;
            const expandIcon = header.querySelector('.expand-icon');

            if (content) {
                const isExpanding = !content.classList.contains('expanded');

                if (isExpanding) {
                    const sidebar = header.closest('.modern-sidebar');
                    if (sidebar) {
                        sidebar.querySelectorAll('.section-header.expandable').forEach(otherHeader => {
                            if (otherHeader !== header) {
                                const otherSection = otherHeader.closest('.sidebar-section');
                                const otherContent = otherSection ? otherSection.querySelector('.section-content') : null;
                                const otherIcon = otherHeader.querySelector('.expand-icon');
                                
                                otherHeader.classList.remove('active');
                                if (otherContent) otherContent.classList.remove('expanded');
                                if (otherIcon) {
                                    otherIcon.style.transform = 'rotate(180deg)';
                                    otherIcon.classList.remove('expanded');
                                }
                            }
                        });
                    }
                }

                content.classList.toggle('expanded');
                header.classList.toggle('active');

                if (expandIcon) {
                    if (content.classList.contains('expanded')) {
                        expandIcon.style.transform = 'rotate(270deg)';
                        expandIcon.classList.add('expanded');
                    } else {
                        expandIcon.style.transform = 'rotate(180deg)';
                        expandIcon.classList.remove('expanded');
                    }
                }
            }
        }
    });

    // Handle non-expandable sections
    document.addEventListener('click', function(e) {
        const header = e.target.closest('.section-header:not(.expandable)');
        if (header && !e.target.closest('a')) {
            // Do nothing special, let default click happen
        }
    });

    // Handle expandable subcategories
    document.addEventListener('click', function(e) {
        const subcat = e.target.closest('.expandable-subcat');
        if (subcat) {
            if (e.target.closest('a') && !e.target.closest('.expand-icon-subcat')) return;

            e.preventDefault();
            const wrapper = subcat.closest('.cmgalaxy-subcat-wrapper');
            const content = wrapper ? wrapper.querySelector('.sub-subcategories') : null;
            const expandIcon = subcat.querySelector('.expand-icon-subcat');
            
            if (content) {
                const isExpanding = content.style.display === 'none' || content.style.display === '';
                
                if (isExpanding) {
                    content.style.display = 'block';
                    if (expandIcon) expandIcon.style.transform = 'rotate(270deg)';
                } else {
                    content.style.display = 'none';
                    if (expandIcon) expandIcon.style.transform = 'rotate(180deg)';
                }
            }
        }
    });

    // Auto-scroll to active item on load
    const activeItems = document.querySelectorAll('.current-page:not(.expandable-subcat), .active-article');
    
    activeItems.forEach(activeItem => {
        // If it's inside a subcategory dropdown, ensure the subcategory is open
        const parentSubcatContent = activeItem.closest('.sub-subcategories');
        if (parentSubcatContent) {
            parentSubcatContent.style.display = 'block';
            const wrapper = parentSubcatContent.closest('.cmgalaxy-subcat-wrapper');
            if (wrapper) {
                const toggleHeader = wrapper.querySelector('.expandable-subcat');
                if (toggleHeader) {
                    const icon = toggleHeader.querySelector('.expand-icon-subcat');
                    if (icon) {
                        icon.style.transform = 'rotate(270deg)';
                    }
                }
            }
        }
        
        // Scroll inside sidebar ONLY on desktop (never hijack window scroll on mobile/page)
        if (window.innerWidth > 1024) {
            const sidebar = activeItem.closest('.modern-sidebar');
            if (sidebar) {
                setTimeout(() => {
                    const itemTop = activeItem.offsetTop;
                    sidebar.scrollTop = itemTop - (sidebar.clientHeight / 2);
                }, 300);
            }
        }
    });
});
