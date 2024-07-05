export interface UserProfile {
  avatar_url : string,
  name : string,
  username : string,
  bio? : string,
  github_link : string,
  blog_link? : string,
  company? : string,
  location? : string,
  public_repos : number,
  followers : number,
  following : number,
}
