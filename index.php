<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>


    <!-- navbar -->

  <?php include_once 'includes/header.php' ?>
    <!-- /navbar -->
    <!-- HERO SECTION -->
    <section class="min-h-screen flex items-center justify-center px-6 mt-20">
        <div class="grid md:grid-cols-2 gap-10 items-center max-w-6xl w-full">

            <!-- LEFT CONTENT -->
            <div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                    Build Your Future With
                    <span class="text-green-700">Confidence</span>
                </h1>

                <p class="mt-4 text-gray-600">
                    We provide reliable solutions to help you grow, succeed, and achieve your dreams with confidence.
                </p>

                <div class="mt-6 flex gap-4">
                    <button class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-lg transition">
                        Get Started
                    </button>

                    <button class="border border-green-700 text-green-700 hover:bg-green-700 hover:text-white px-6 py-3 rounded-lg transition">
                        Learn More
                    </button>
                </div>
            </div>

            <!-- RIGHT IMAGE -->
            <div class="flex justify-center">
                <img
                    src="https://images.unsplash.com/photo-1556157382-97eda2d62296"
                    alt="Hero Image"
                    class="rounded-2xl shadow-lg w-full max-w-md">
            </div>

        </div>
    </section>
   

</body>

</html>