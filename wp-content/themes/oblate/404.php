<?php 

get_header(); 
?>
<style>
    @keyframes blink {
        0% {opacity: 0}
        49%{opacity: 0}
        50% {opacity: 1}
    }

.blink { animation: blink 1s infinite; }
</style> 

    <section class="not-found">
        <div class="container">
            <h1><span>404</span></h1>
            <p>A fatal exception 0E has occurred at 74616e6961 in 404: page not found.</p>
            <p class="bsod-list">* &nbsp; Click any link to terminate the current application.</p>
            <p class="text-right">Click any link to continue <span class="blink">_</span></p>
        </div>
    </section>

<?php 

get_footer();