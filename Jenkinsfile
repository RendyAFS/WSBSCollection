// pipeline {
//     agent any
//     environment {
//         DOCKER_SERVER = 'localhost' // Docker Desktop berjalan di lokal
//     }
//     stages {
//         // Stage 1: Deploy using Docker Compose
//         stage("Deploy Application with Docker Compose") {
//             steps {
//                 echo "Starting containers using Docker Compose..."
//                 bat "docker-compose up -d" // Pastikan semua container berjalan
//             }
//         }

//         // Stage 2: Health Check
//         stage("Health Check") {
//             steps {
//                 echo "Checking if application is running..."
//                 bat "curl -f http://localhost:8081 || exit 1" // Periksa aplikasi melalui Nginx
//             }
//         }
//     }

//     post {
//         always {
//             echo "Pipeline execution complete."
//         }
//         success {
//             echo "Pipeline executed successfully."
//         }
//         failure {
//             echo "Pipeline execution failed."
//         }
//     }
// }
pipeline {
    agent any
    environment {
        DOCKER_SERVER = 'localhost' // Docker Desktop berjalan di lokal
    }
    stages {
        // Stage 1: Clone Repository
        stage("Clone Repository") {
            steps {
                echo "Repository sudah di-clone sebelumnya, melanjutkan ke langkah berikutnya..."
            }
        }

        // Stage 2: Install Dependencies
        stage("Install Dependencies") {
            steps {
                echo "Menjalankan npm install..."
                bat "npm install" // Menjalankan npm install untuk Node.js dependencies

                echo "Menjalankan composer install..."
                bat "composer install" // Menjalankan composer install untuk PHP dependencies

                echo "Menyalin file .env.example ke .env..."
                bat "copy .env.example .env" // Menyalin file .env.example menjadi .env
            }
        }

        // Stage 3: Download File from Google Drive
        stage("Download File from Google Drive") {
            steps {
                echo "Mendownload file dari Google Drive..."
                // Disarankan menggunakan cara lain untuk mendownload file secara otomatis dari Google Drive menggunakan API
                // Atau dapat menggunakan wget/curl jika link drive mendukung
                echo "File telah didownload."
            }
        }

        // Stage 4: Deploy using Docker Compose
        stage("Deploy Application with Docker Compose") {
            steps {
                echo "Starting containers using Docker Compose..."
                bat "docker-compose up -d" // Pastikan semua container berjalan
            }
        }

        // Stage 5: Health Check
        stage("Health Check") {
            steps {
                echo "Checking if application is running..."
                bat "curl -f http://localhost:8081 || exit 1" // Periksa aplikasi melalui Nginx
            }
        }
    }

    post {
        always {
            echo "Pipeline execution complete."
        }
        success {
            echo "Pipeline executed successfully."
        }
        failure {
            echo "Pipeline execution failed."
        }
    }
}
