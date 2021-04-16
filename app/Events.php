<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    public static $tbl = 'events';
    protected $table = 'events';
    public static function getData($data = [], $single = false, $num_rows = false){
        $query = DB::table(self::$tbl);
		if ($num_rows) {
          	$query->select(
				DB::raw("COUNT(".self::$tbl.".id) as totalRecord")
			);
		} else {
			$query->select(self::$tbl. '.*');
        }

		if (isset($data['search']) && !empty($data['search'])) {
			$search = $data['search'];
			$query->where(function($qu) use ($search){
				$qu->orWhere(self::$tbl. '.comments', 'like', '%'.$search.'%');				
			});
		}

		if (isset($data['id']) && !empty($data['id'])) {
			if (is_array($data['id'])) {
				$query->whereIn(self::$tbl. '.id', $data['id']);
			} else {
				$query->Where(self::$tbl. '.id', $data['id']);
			}
        }
        
		if (isset($data['events_type']) && !empty($data['events_type'])) {
			$query->Where(self::$tbl. '.events_type', $data['events_type']);
		}

		if (isset($data['winner_id']) && !empty($data['winner_id'])) {
			$query->Where(self::$tbl. '.winner_id', $data['winner_id']);
		}
        
		if (isset($data['looser_id']) && !empty($data['looser_id'])) {
			$query->Where(self::$tbl. '.looser_id', $data['looser_id']);
		}
        
		if (isset($data['amount']) && !empty($data['amount'])) {
			$query->Where(self::$tbl. '.amount', $data['amount']);
		}
        
		if (isset($data['date_time']) && !empty($data['date_time'])) {
			$query->Where(self::$tbl. '.date_time', $data['date_time']);
		}
        
		if (isset($data['comments']) && !empty($data['comments'])) {
			$query->Where(self::$tbl. '.comments', $data['comments']);
		}
        
		if (isset($data['is_manual']) && !empty($data['is_manual'])) {
			$query->Where(self::$tbl. '.is_manual', $data['is_manual']);
		}

		if (isset($data['created_by'])) {
			$query->Where(self::$tbl. '.created_by', $data['created_by']);
		}
		
    	if (isset($data['status'])) {
			if (is_array($data['status'])) {
				$query->whereIn(self::$tbl. '.status', $data['status']);
			} else {
				$query->Where(self::$tbl. '.status', $data['status']);
			}
		}

		if (isset($data['created_at'])) {
			$query->Where(self::$tbl. '.created_at', $data['created_at']);
		}

		if (isset($data['updated_at'])) {
			$query->Where(self::$tbl. '.updated_at', $data['updated_at']);
		}

		if (!$num_rows) {
			if (isset($data['length']) && isset($data['start'])) {
				$query->limit($data['length']);
				$query->offset($data['start']);
			} elseif (isset($data['length']) && !empty($data['length'])) {
				$query->limit($data['length']);
			} else {
				//$query->limit(10);
			}
		}

		if (isset($data['orderby']) && !empty($data['orderby'])) {
			$query->orderBy(self::$tbl. '.'.$data['orderby'], (isset($data['orderstate']) && !empty($data['orderstate']) ? $data['orderstate'] : 'DESC'));
		} else {
			$query->orderBy(self::$tbl. '.id', 'DESC');
		}

		if ($num_rows) {
            $row = $query->first();
            return isset($row->totalRecord)?$row->totalRecord:"0";
        }

		if ($single) {
			return $query->first();
		}
		return $query->get();
    }

    public static function setData($data, $id = 0) {
		if (empty($data)) {
			return false;
		}
		$modelData = array();
		if (isset($data['events_type'])) {
			$modelData['events_type'] = $data['events_type'];
        }
		if (isset($data['winner_id'])) {
			$modelData['winner_id'] = $data['winner_id'];
        }
		if (isset($data['looser_id'])) {
			$modelData['looser_id'] = $data['looser_id'];
        }
		if (isset($data['amount'])) {
			$modelData['amount'] = $data['amount'];
        }
		if (isset($data['date_time'])) {
			$modelData['date_time'] = $data['date_time'];
        }
		if (isset($data['comments'])) {
			$modelData['comments'] = $data['comments'];
        }
		if (isset($data['is_manual'])) {
			$modelData['is_manual'] = $data['is_manual'];
        }
		if (isset($data['created_by'])) {
			$modelData['created_by'] = $data['created_by'];
        }
		if (isset($data['status'])) {
			$modelData['status'] = $data['status'];
		}
		if (empty($modelData)) {
			return false;
		}		
		if(empty($id)){
			$modelData['created_at'] = isset($data['created_at']) && !empty($data['created_at']) ? $data['created_at'] : date('Y-m-d H:i:s');
		} else {
			$modelData['updated_at'] = date('Y-m-d H:i:s');
		}

		$query = DB::table(self::$tbl);
		if (!empty($id)) {
			if(is_array($id)){
				$query->whereIn('id', $id);
				$query->update($modelData);
			}else{
				$query->where('id', $id);
				$query->update($modelData);
			}
		} else {
			$id = $query->insertGetId($modelData);
		}
		return $id;
    }
}
