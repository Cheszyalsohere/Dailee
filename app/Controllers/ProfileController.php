<?php

namespace App\Controllers;

class ProfileController extends BaseController
{
    public function index(?string $username = null)
    {
        $db = \Config\Database::connect();
        $viewerId = (int) (session()->get('id') ?? session()->get('user_id'));
        $profile = $username
            ? $db->table('users')->where('username', $username)->get()->getRowArray()
            : $db->table('users')->where('id', $viewerId)->get()->getRowArray();

        if (!$profile) {
            return redirect()->to('/profile')->with('error', 'Profil tidak ditemukan.');
        }

        $profileId = (int) $profile['id'];
        $isOwnProfile = $profileId === $viewerId;
        $isFriend = !$isOwnProfile && $db->table('friendships')
            ->where('user_id', $viewerId)->where('friend_id', $profileId)
            ->where('status', 'accepted')->countAllResults() > 0;

        $friendState = null;
        if (!$isOwnProfile && !$isFriend) {
            $friendState = $db->table('friendships')
                ->where('user_id', $viewerId)->where('friend_id', $profileId)
                ->where('status', 'pending')->get()->getRowArray() ? 'sent' : null;
            if ($friendState === null && $db->table('friendships')
                ->where('user_id', $profileId)->where('friend_id', $viewerId)
                ->where('status', 'pending')->countAllResults() > 0) {
                $friendState = 'received';
            }
        }

        $followerCount = 0;
        $followingCount = 0;
        $isFollowing = false;
        if ($db->tableExists('follows')) {
            $followerCount = $db->table('follows')->where('following_id', $profileId)->countAllResults();
            $followingCount = $db->table('follows')->where('follower_id', $profileId)->countAllResults();
            $isFollowing = !$isOwnProfile && $db->table('follows')
                ->where('follower_id', $viewerId)->where('following_id', $profileId)
                ->countAllResults() > 0;
        }

        $totalFriends = $db->table('friendships')
            ->where('user_id', $profileId)->where('status', 'accepted')->countAllResults();

        $momentQuery = $db->table('moments')
            ->select('moments.*, schedules.title as agenda_title')
            ->join('schedules', 'schedules.id = moments.schedule_id', 'left')
            ->where('moments.user_id', $profileId);

        if (!$isOwnProfile && !$isFriend) {
            $momentQuery->where('moments.visibility', 'public');
        }

        $moments = $momentQuery->orderBy('moments.created_at', 'DESC')->get()->getResultArray();
        $today = date('Y-m-d');
        $todayMoment = null;
        foreach ($moments as $moment) {
            if (!empty($moment['created_at']) && substr($moment['created_at'], 0, 10) === $today) {
                $todayMoment = $moment;
                break;
            }
        }

        return view('profile/index', [
            'user' => $profile,
            'moments' => $moments,
            'todayMoment' => $todayMoment,
            'totalFriends' => $totalFriends,
            'followerCount' => $followerCount,
            'followingCount' => $followingCount,
            'isOwnProfile' => $isOwnProfile,
            'isFriend' => $isFriend,
            'friendState' => $friendState,
            'isFollowing' => $isFollowing,
        ]);
    }

    public function updateAvatar()
    {
        $db = \Config\Database::connect();
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        $file = $this->request->getFile('avatar');
        $uploadPath = FCPATH . 'uploads/avatars/';

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->to('/profile')->with('error', 'Pilih foto profil yang valid.');
        }
        if (!in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return redirect()->to('/profile')->with('error', 'Foto harus berformat JPG, PNG, atau WebP.');
        }

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $currentUser = $db->table('users')->where('id', $userId)->get()->getRowArray();
        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);
        $db->table('users')->where('id', $userId)->update(['avatar' => $newName]);

        if (!empty($currentUser['avatar']) && is_file($uploadPath . $currentUser['avatar'])) {
            @unlink($uploadPath . $currentUser['avatar']);
        }

        return redirect()->to('/profile')->with('message', 'Foto profil berhasil diperbarui.');
    }

    public function updateDetails()
    {
        $db = \Config\Database::connect();
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        $values = [
            'nama_lengkap' => trim((string) $this->request->getPost('nama_lengkap')),
            'location' => trim((string) $this->request->getPost('location')),
            'occupation' => trim((string) $this->request->getPost('occupation')),
            'education' => trim((string) $this->request->getPost('education')),
            'zodiac_or_interest' => trim((string) $this->request->getPost('zodiac_or_interest')),
            'bio' => trim((string) $this->request->getPost('bio')),
        ];

        foreach (array_keys($values) as $field) {
            if (!$db->fieldExists($field, 'users')) {
                unset($values[$field]);
            }
        }
        $db->table('users')->where('id', $userId)->update($values);

        return redirect()->to('/profile')->with('message', 'Profil berhasil disimpan.');
    }

    public function deleteMoment($id)
    {
        $db = \Config\Database::connect();
        $userId = (int) (session()->get('id') ?? session()->get('user_id'));
        $moment = $db->table('moments')->where('id', $id)->where('user_id', $userId)->get()->getRowArray();

        if ($moment) {
            $uploadPath = FCPATH . 'uploads/moments/';
            foreach (['main_image', 'inset_image', 'photo_path'] as $column) {
                if (!empty($moment[$column]) && is_file($uploadPath . $moment[$column])) {
                    @unlink($uploadPath . $moment[$column]);
                }
            }
            $db->table('moments')->where('id', $id)->where('user_id', $userId)->delete();
        }

        return redirect()->to('/profile')->with('message', 'Daily log berhasil dihapus.');
    }
}
