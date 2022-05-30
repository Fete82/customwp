<?php

class class_GithubApi {

	private string $token;
	private string $username;
	private string $apiUrl = 'https://api.github.com';

	function __construct() {
		$this->token    = get_option( 'wcm_github_token' );
		$this->username = get_option( 'wcm_github_username' );
	}

	public function getUserData()
	{
		$url = $this->apiUrl . '/users/' . $this->username;
		$response = $this->makeApiCall( $url );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return json_decode( $response, true );
	}

	public function getRepos() {
		$repos = get_transient( 'wcm_github_user_repos' );
		$repoUrl = $this->apiUrl . '/users/' . $this->username . '/repos';

		if ( $repos === false ) {
			$repos = $this->makeApiCall($repoUrl);
			set_transient( 'wcm_github_user_repos', $repos, 60 * 60 );
		}

		return json_decode( $repos, true );
	}

	protected function makeApiCall($url): string {
		$args = [
			'headers' => [
				'Accept'        => 'application/vnd.github.v3+json',
				'Authorization' => 'Basic ' . base64_encode( $this->username . ': ' . $this->token ),

			],
		];
		$response = wp_remote_get( $url );

		return wp_remote_retrieve_body( $response );
	}

}
