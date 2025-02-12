<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;

use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\HistoryUserModel;

class Home extends BaseController
{
	public function login()
	{
		return view('login');
	}

	public function register()
	{
		$userModel = new UserModel();
		$historyModel = new HistoryUserModel();

		$username = $this->request->getPost('username');
		$email    = $this->request->getPost('email');
		$password = $this->request->getPost('password');

		if (empty($username) || empty($email) || empty($password)) {
			return $this->response->setJSON(['status' => 'error', 'message' => 'Semua field harus diisi!']);
		}

		if ($userModel->where('username', $username)->first()) {
			return $this->response->setJSON(['status' => 'error', 'message' => 'Username sudah terdaftar!']);
		}

		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		$user_id = $userModel->insert([
			'username' => $username,
			'email'    => $email,
			'password' => $hashedPassword
		], true); // Mengambil ID yang baru diinsert

		// Catat ke history_user
		$historyModel->insert([
			'user_id' => $user_id,
			'activity_type' => 'register',
			'user_agent' => $this->request->getUserAgent()->getAgentString()
		]);

		return $this->response->setJSON(['status' => 'success', 'message' => 'Akun berhasil dibuat!']);
	}

	public function doLogin()
	{
		$userModel = new UserModel();
		$historyModel = new HistoryUserModel();

		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');

		$user = $userModel->where('username', $username)->first();

		if ($user && password_verify($password, $user['password'])) {
			session()->set([
				'user_id'   => $user['id'],
				'username'  => $user['username'],
				'logged_in' => true
			]);

			// Catat ke history_user
			$historyModel->insert([
				'user_id' => $user['id'],
				'activity_type' => 'login',
				'user_agent' => $this->request->getUserAgent()->getAgentString()
			]);

			return redirect()->to('home/dashboard');
		} else {
			return redirect()->to('home/login')->with('error', 'Username atau password salah!');
		}
	}

	public function dashboard()
	{
		// Cek apakah user sudah login
		if (!session()->get('logged_in')) {
			return redirect()->to('home/login')->with('error', 'Silakan login terlebih dahulu!');
		}

		$postModel = new PostModel();
		$userModel = new UserModel();

		// Ambil semua postingan dengan informasi pengguna
		$posts = $postModel->orderBy('created_at', 'DESC')->findAll();

		foreach ($posts as &$post) {
			$user = $userModel->find($post['user_id']);
			$post['username'] = $user ? $user['username'] : 'Unknown';
		}

		// Ambil informasi pengguna yang sedang login
		$userInfo = $userModel->find(session()->get('user_id'));

		echo view('menu');
		echo view('dashboard', [
			'posts'   => $posts,
			'userInfo' => $userInfo
		]);
	}

	public function createPost()
	{
		if (!session()->get('logged_in')) {
			return redirect()->to('home/login')->with('error', 'Silakan login terlebih dahulu!');
		}

		$postModel = new PostModel();
		$user_id = session()->get('user_id');

		// Cek apakah ada file yang diupload
		$file = $this->request->getFile('media');
		if ($file->isValid() && !$file->hasMoved()) {
			$newName = $file->getRandomName();
			$file->move('uploads', $newName); // Simpan file

			// Simpan ke database
			$postModel->insert([
				'user_id' => $user_id,
				'media'   => $newName,
				'caption' => $this->request->getPost('caption')
			]);

			return redirect()->to('home/dashboard')->with('success', 'Post berhasil dibuat!');
		} else {
			return redirect()->to('home/dashboard')->with('error', 'Gagal mengunggah file!');
		}
	}

	public function logout()
	{
		session()->destroy();
		return redirect()->to('home/login')->with('message', 'Anda telah logout.');
	}

	public function historyUser()
	{
		$historyModel = new HistoryUserModel();
		$userModel = new UserModel();

		$start_date = $this->request->getGet('start_date');
		$end_date = $this->request->getGet('end_date');
		$username = $this->request->getGet('username');
		$activity = $this->request->getGet('activity');
		$keyword = $this->request->getGet('keyword');

		$query = $historyModel->orderBy('activity_time', 'DESC');

		if ($start_date && $end_date) {
			$query->where("activity_time >=", $start_date)
				->where("activity_time <=", $end_date . ' 23:59:59');
		}

		if ($username) {
			$user = $userModel->where('username', $username)->first();
			if ($user) {
				$query->where('user_id', $user['id']);
			}
		}

		if ($activity) {
			$query->where('activity_type', $activity);
		}

		if ($keyword) {
			$query->groupStart()
				->like('activity_type', $keyword)
				->orLike('activity_time', $keyword)
				->orLike('user_agent', $keyword)
				->groupEnd();
		}

		$history = $query->findAll();

		foreach ($history as &$h) {
			$user = $userModel->find($h['user_id']);
			$h['username'] = $user ? $user['username'] : 'Unknown';
		}

		echo view ('menu');
		echo view('history_user', [
			'history' => $history,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'username' => $username,
			'activity' => $activity,
			'keyword' => $keyword
		]);
	}

	public function downloadHistoryPdf()
	{
		$historyModel = new HistoryUserModel();
		$userModel = new UserModel();

		$start_date = $this->request->getGet('start_date');
		$end_date = $this->request->getGet('end_date');
		$username = $this->request->getGet('username');
		$activity = $this->request->getGet('activity');
		$keyword = $this->request->getGet('keyword');

		$query = $historyModel->orderBy('activity_time', 'DESC');

		if ($start_date && $end_date) {
			$query->where("activity_time >=", $start_date)
				->where("activity_time <=", $end_date . ' 23:59:59');
		}

		if ($username) {
			$user = $userModel->where('username', $username)->first();
			if ($user) {
				$query->where('user_id', $user['id']);
			}
		}

		if ($activity) {
			$query->where('activity_type', $activity);
		}

		if ($keyword) {
			$query->groupStart()
				->like('activity_type', $keyword)
				->orLike('activity_time', $keyword)
				->orLike('user_agent', $keyword)
				->groupEnd();
		}

		$history = $query->findAll();

		foreach ($history as &$h) {
			$user = $userModel->find($h['user_id']);
			$h['username'] = $user ? $user['username'] : 'Unknown';
		}

		$html = "
    <h2 style='text-align: center;'>Laporan Aktivitas User</h2>
    <table border='1' width='100%' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Activity</th>
            <th>Waktu</th>
            <th>User Agent</th>
        </tr>";

		foreach ($history as $h) {
			$html .= "
        <tr>
            <td>{$h['id']}</td>
            <td>{$h['username']}</td>
            <td>{$h['activity_type']}</td>
            <td>{$h['activity_time']}</td>
            <td>{$h['user_agent']}</td>
        </tr>";
		}

		$html .= "</table>";

		$options = new Options();
		$options->set('defaultFont', 'Helvetica');
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		$filename = 'Laporan_History_User_' . date('YmdHis') . '.pdf';
		return $this->response->setHeader('Content-Type', 'application/pdf')
			->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
			->setBody($dompdf->output());
	}
}
