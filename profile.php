<?php 
/**
 * ================================================================
 * PROFILE PAGE - User Account Management
 * ================================================================
 * 
 * Features:
 * - Display user profile information
 * - Avatar with user initial
 * - Reveal/Hide sensitive data (Email & Phone)
 * - Edit profile functionality (Modal - To be implemented)
 * - Clean and organized layout
 * 
 * Structure:
 * 1. User authentication check
 * 2. Fetch user data from database
 * 3. Display profile card with:
 *    - Header with gradient background
 *    - Avatar and user name
 *    - User information (Display Name, Username, Email, Phone)
 *    - Member since date
 * 4. JavaScript functions for interactions
 * 
 * TODO:
 * - Implement floating modal for edit functionality
 * - Add form validation in modal
 * - Implement remove phone functionality
 * 
 * ================================================================
 */

session_start();
include 'config/db_connect.php';

// 1. Cek Login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['user_id'];

// 2. Ambil Data User Terbaru
$query = mysqli_query($conn, "SELECT * FROM akun_user WHERE id_user = '$id_user'");
$user = mysqli_fetch_array($query);

// 3. Ambil Pesan Notifikasi (Jika ada update)
$msg_type = $_SESSION['msg_type'] ?? '';
$msg_content = $_SESSION['msg_content'] ?? '';

// Hapus session pesan agar tidak muncul terus saat refresh
unset($_SESSION['msg_type']);
unset($_SESSION['msg_content']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/styles/style_user.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden">

    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-1 h-full overflow-y-auto bg-gray-50 p-6 lg:p-10 scroll-smooth">
        
        <div class="max-w-5xl mx-auto pb-20">
            
            <div class="mb-8 animate-fade-in-down">
                <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
                <p class="text-gray-500 mt-1">Kelola informasi akun dan keamanan Anda.</p>
            </div>

            <?php if($msg_type): ?>
                <div class="mb-6 p-4 rounded-xl flex items-center gap-3 animate-fade-in-up <?= $msg_type == 'success' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' ?>">
                    <i class="fa-solid <?= $msg_type == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> text-xl"></i>
                    <p class="font-medium"><?= $msg_content ?></p>
                </div>
            <?php endif; ?>

            <!-- Card Profil Gabungan -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden animate-fade-in-up max-w-4xl">
                
                <!-- Background Header Biru -->
                <div class="h-36 bg-gradient-to-r from-blue-600 to-blue-400 relative"></div>
                
                <!-- Konten Profil -->
                <div class="px-10 pb-10 -mt-20 relative">
                    
                    <!-- Header: Avatar + Nama -->
                    <div class="flex items-start mb-10">
                        <div class="flex items-center gap-6">
                            <!-- Avatar Circle -->
                            <div class="w-32 h-32 rounded-full bg-white p-2.5 shadow-xl ring-4 ring-white">
                                <div class="w-full h-full rounded-full bg-blue-100 flex items-center justify-center text-5xl font-bold text-blue-600">
                                    <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                                </div>
                            </div>
                            
                            <!-- Nama User -->
                            <div class="mt-24">
                                <h2 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($user['nama']) ?></h2>
                                <p class="text-gray-500 text-sm mt-1">Member sejak <?= date('F Y', strtotime($user['tanggal_daftar'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 mb-8"></div>

                    <!-- Informasi Akun -->
                    <div class="space-y-1">
                        
                        <!-- Display Name -->
                        <div class="flex items-center justify-between py-5 border-b border-gray-100 hover:bg-gray-50 px-4 rounded-lg transition-all">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Display Name</label>
                                <p class="text-gray-900 font-semibold text-lg"><?= htmlspecialchars($user['nama']) ?></p>
                            </div>
                            <button type="button" onclick="openEditModal('nama')" 
                                    class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-semibold transition-all">
                                Edit
                            </button>
                        </div>

                        <!-- Username (Email) -->
                        <div class="flex items-center justify-between py-5 border-b border-gray-100 hover:bg-gray-50 px-4 rounded-lg transition-all">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Username</label>
                                <p class="text-gray-900 font-semibold text-lg"><?= htmlspecialchars($user['email']) ?></p>
                            </div>
                            <button type="button" onclick="openEditModal('username')" 
                                    class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-semibold transition-all">
                                Edit
                            </button>
                        </div>

                        <!-- Email (Hidden with Reveal) -->
                        <div class="flex items-center justify-between py-5 border-b border-gray-100 hover:bg-gray-50 px-4 rounded-lg transition-all">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Email</label>
                                <div class="flex items-center gap-4">
                                    <p class="text-gray-900 font-semibold text-lg">
                                        <span id="emailHidden">************@gmail.com</span>
                                        <span id="emailRevealed" style="display: none;"><?= htmlspecialchars($user['email']) ?></span>
                                    </p>
                                    <button type="button" onclick="toggleReveal('email')" 
                                            class="text-blue-600 hover:text-blue-700 text-sm font-bold underline">
                                        <span id="emailRevealText">Reveal</span>
                                    </button>
                                </div>
                            </div>
                            <button type="button" onclick="openEditModal('email')" 
                                    class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-semibold transition-all">
                                Edit
                            </button>
                        </div>

                        <!-- Phone Number (Hidden with Reveal) -->
                        <div class="flex items-center justify-between py-5 border-b border-gray-100 hover:bg-gray-50 px-4 rounded-lg transition-all">
                            <div class="flex-1">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Phone Number</label>
                                <div class="flex items-center gap-4">
                                    <p class="text-gray-900 font-semibold text-lg">
                                        <span id="phoneHidden">**********<?= substr($user['no_telpon'], -4) ?></span>
                                        <span id="phoneRevealed" style="display: none;"><?= htmlspecialchars($user['no_telpon']) ?></span>
                                    </p>
                                    <button type="button" onclick="toggleReveal('phone')" 
                                            class="text-blue-600 hover:text-blue-700 text-sm font-bold underline">
                                        <span id="phoneRevealText">Reveal</span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" onclick="openEditModal('phone')" 
                                        class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-lg text-sm font-semibold transition-all">
                                    Edit
                                </button>
                            </div>
                        </div>



                    </div>

                        </div>

                </div>
            </div>

            <!-- Floating Modal untuk Edit (Prepared - akan dikerjakan nanti) -->
            <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Profile</h3>
                    <p class="text-gray-600">Modal edit akan diimplementasikan nanti...</p>
                    <button onclick="closeEditModal()" class="mt-4 bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg">Close</button>
                </div>
            </div>

        </div>
    </main>

    <script>
        // ==============================================
        // MODAL FUNCTIONS (Prepared for future implementation)
        // ==============================================
        
        /**
         * Open edit modal with specific field
         * @param {string} field - Field to edit: 'nama', 'username', 'email', 'phone', or null for all
         */
        function openEditModal(field = null) {
            // TODO: Implement modal functionality
            console.log('Opening edit modal for:', field || 'all fields');
            // const modal = document.getElementById('editModal');
            // modal.classList.remove('hidden');
            alert('Edit modal akan diimplementasikan nanti. Field: ' + (field || 'All'));
        }
        
        /**
         * Close edit modal
         */
        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
        }
        
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeEditModal();
            }
        });

        // ==============================================
        // REVEAL/HIDE FUNCTIONS
        // ==============================================
        
        /**
         * Toggle reveal for email and phone
         * @param {string} type - 'email' or 'phone'
         */
        function toggleReveal(type) {
            if (type === 'email') {
                const hidden = document.getElementById('emailHidden');
                const revealed = document.getElementById('emailRevealed');
                const revealText = document.getElementById('emailRevealText');
                
                if (hidden.style.display === 'none') {
                    hidden.style.display = 'inline';
                    revealed.style.display = 'none';
                    revealText.textContent = 'Reveal';
                } else {
                    hidden.style.display = 'none';
                    revealed.style.display = 'inline';
                    revealText.textContent = 'Hide';
                }
            } else if (type === 'phone') {
                const hidden = document.getElementById('phoneHidden');
                const revealed = document.getElementById('phoneRevealed');
                const revealText = document.getElementById('phoneRevealText');
                
                if (hidden.style.display === 'none') {
                    hidden.style.display = 'inline';
                    revealed.style.display = 'none';
                    revealText.textContent = 'Reveal';
                } else {
                    hidden.style.display = 'none';
                    revealed.style.display = 'inline';
                    revealText.textContent = 'Hide';
                }
            }
        }

        // ==============================================
        // REMOVE PHONE FUNCTION
        // ==============================================
        
        /**
         * Remove phone number (with confirmation)
         */
        function removePhone() {
            if (confirm('Apakah Anda yakin ingin menghapus nomor telepon?')) {
                // TODO: Implement remove phone functionality
                console.log('Removing phone number...');
                alert('Fungsi remove phone akan diimplementasikan nanti');
            }
        }

        // ==============================================
        // ANIMATIONS
        // ==============================================
        
        /**
         * Smooth scroll animations on page load
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation classes
            const elements = document.querySelectorAll('.animate-fade-in-up, .animate-fade-in-down');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>