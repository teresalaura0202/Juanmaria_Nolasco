
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.counter');
    const speed = 200; // Velocidad de la animación (menor = más rápido)
    
    counters.forEach(counter => {
        const animate = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            
            // Calcula la velocidad basada en el valor objetivo
            const inc = target / speed;
            
            if (count < target) {
                // Incrementa y redondea
                counter.innerText = Math.ceil(count + inc);
                // Llama a la función de nuevo
                setTimeout(animate, 1);
            } else {
                counter.innerText = target;
            }
        };
        
        // Inicia la animación cuando la página se carga
        animate();
    });
    
    // Opcional: Reiniciar contadores cuando están en viewport
    const observerOptions = {
        threshold: 0.5
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.counter');
                counters.forEach(counter => {
                    counter.innerText = '0';
                    const animate = () => {
                        const target = +counter.getAttribute('data-target');
                        const count = +counter.innerText;
                        const inc = target / speed;
                        
                        if (count < target) {
                            counter.innerText = Math.ceil(count + inc);
                            setTimeout(animate, 1);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    animate();
                });
            }
        });
    }, observerOptions);
    
    const counterContainer = document.querySelector('.row');
    observer.observe(counterContainer);
});
