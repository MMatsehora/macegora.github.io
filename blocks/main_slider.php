<?php if (!defined('ALLOW')) exit ( 'Error 404 wrong way to file' );

echo '
<section>
    <h1>'.$h1.'</h1>';
?>
    <div class="carousel slide" id="carousel-example-generic"  data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">

            <div class="item active">
                <img src="img/slider/1.jpg" alt="Габионы1" width="880" height="440" />
                <div class="carousel-caption" >
                    <h3>Качественные материалы</h3>
                    <p>Для реализации любых задумок в ландшафтном дизайне и геотехническом строительстве</p>
                </div>
            </div>

            <div class="item">
                <img src="img/slider/2.jpg" alt="Габионы1" width="880" height="440" />
                <div class="carousel-caption" >
                    <h3>Габионные конструкции</h3>
                    <p>Современные технологии для укрепления берегов, склонов и террасирования участков</p>
                </div>
            </div>

            <div class="item">
                <img src="img/slider/3.jpg" alt="Габионы1" width="880" height="440" />
                <div class="carousel-caption" >
                    <h3>Габионная сетка</h3>
                    <p>Реализация сложных конструкторских решений с минимальными затратами</p> 
                </div>
            </div>

        </div>
    </div>
</section>