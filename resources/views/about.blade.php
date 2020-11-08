@extends('layouts.base')

@section('title', 'About me and this website')

@section('content')
    <section>
        <h2>Lorem ipsum dolor sit amet consectetur</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur assumenda natus culpa laudantium vero, molestiae autem nobis, quaerat adipisci quasi sit voluptatum quos, nostrum dolorum ducimus voluptatem porro nesciunt earum.lore Lorem ipsum dolor sit, amet consectetur adipisicing elit. Natus deserunt, id, error aut ipsam porro eos pariatur suscipit blanditiis voluptates tempora ratione odio veritatis fuga at quam, sed sit laudantium. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo fugit minima reiciendis quibusdam minus quam, veritatis nihil id, vitae quas fugiat, ut molestiae dolores eos laboriosam molestias? Rerum, impedit laborum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel deleniti ea tempora? Architecto asperiores eius reiciendis voluptate. Necessitatibus repellat officiis unde vitae adipisci iste placeat repudiandae, quos rerum iusto totam.</p>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        function downloadJSAtOnload() {

            const element = document.createElement("script");
            element.src = "{{ asset('assets/js/main.js') }}";
            document.body.appendChild(element);

        }

        if (window.addEventListener)
            window.addEventListener("load", downloadJSAtOnload, false);
        else if (window.attachEvent)
            window.attachEvent("onload", downloadJSAtOnload);
        else window.onload = downloadJSAtOnload;
    </script>
@endsection