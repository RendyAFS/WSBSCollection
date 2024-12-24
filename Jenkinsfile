pipeline {
    agent any
    environment {
        DOCKER_SERVER = 'localhost' // Docker Desktop berjalan di lokal
    }
    stages {
        // Stage 1: Deploy using Docker Compose
        stage("Deploy Application with Docker Compose") {
            steps {
                echo "Starting containers using Docker Compose..."
                bat "docker-compose up -d" // Pastikan semua container berjalan
            }
        }

        // Stage 2: Health Check
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
