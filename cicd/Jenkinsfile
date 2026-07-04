pipeline {
    agent any
    
    stages {
        stage('Test Code') {
            steps {
                echo '🚀 Jenkins da tu dong build khi co commit moi!'
                echo '📁 Danh sach file trong repo:'
                sh 'ls -la'
            }
        }
        
        stage('Check Laravel') {
            steps {
                echo '🔍 Kiem tra file Laravel:'
                sh 'cat composer.json | head -20'
            }
        }
    }
}
