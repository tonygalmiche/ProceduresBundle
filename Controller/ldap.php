<?php

namespace OVE\ProceduresBundle\Controller;

class ldap
{
    private $ress;
    private $dn;
    private $bind;

    public function __construct($em) {
      $this->ress=false;
      $this->bind=false;
      $choix_association=@$_COOKIE["association"];
      if($choix_association>0) {
        $association = $em->getRepository('OVEAuthentificationBundle:Association')->find($choix_association);
        $host     = $association->getLdapServerAdress();
        $this->dn = $association->getLdapDn();

        $this->ress = ldap_connect($host, 389);
        ldap_set_option($this->ress, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ress, LDAP_OPT_REFERRALS,true);
        $this->bind=@ldap_bind($this->ress);
      }
    }

    public function getName($login) {
      $name=$login;
      if($this->ress && $this->bind) {
        $filter="uid=$login";
        $justthese = array("ou", "sn", "givenname", "mail"); // givenName: Tony, sn: Galmiche
        $search=ldap_search($this->ress, $this->dn, $filter, $justthese);
        if ($search) {
          $entries = ldap_get_entries($this->ress, $search);
          if(array_key_exists(0,$entries)) {
            $v=$entries[0];
            $name=$v["givenname"][0]." ".$v["sn"][0];
          }
        }
      }
      return $name;
    }

    public function getNames($q) {
      $results=array();
      if($this->ress && $this->bind) {
        $filter="uid=*";
        if($q<>"") $filter="uid=*$q*";
        $justthese = array("uid", "ou", "sn", "givenname", "mail"); // givenname: Tony, sn: Galmiche, uid=tony.galmiche
        $sizelimit=20;
        $search=@ldap_search($this->ress, $this->dn, $filter, $justthese, 0, $sizelimit);
        if ($search) {
          $entries = ldap_get_entries($this->ress, $search);
          $nb=$entries["count"];
          $tab=array();
          for($i=0;$i<$nb;$i++) {
            $v=$entries[$i];
            if(array_key_exists(0,$v["uid"])) {
              $id=$v["uid"][0];
              $givenname=""; $sn="";
              if(array_key_exists("givenname",$v)) $givenname=$v["givenname"][0];
              if(array_key_exists("sn",$v))        $sn=$v["sn"][0];
              $name=$givenname." ".$sn;
              $tab[$id]=array("id"=>$id,"text"=>$name);
            }
          }
          ksort($tab);
          foreach($tab as $v) {
            $results[]=$v;
          }
        }
      }
      return $results;
    }



      //  $results[]=array("id"=>"$i","text"=>"Test $q $i");


}



